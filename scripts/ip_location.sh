#!/bin/sh

# Script to collect data
# and put the data into outputfile

CWD=$(dirname $0)
CACHEDIR="$CWD/cache/"
OUTPUT_FILE="${CACHEDIR}ip_location.txt"
SEPARATOR=' = '

prefs="/Library/Preferences/com.gal.iplocation.plist"
token=$(defaults read $prefs Token)

# Function to extract value from JSON
extract_value() {
    echo "$1" | sed -n 's/.*"'$2'": "\(.*\)".*/\1/p'
}

# Improved IP validation function
validate_ip() {
    echo "$1" | grep -E '^([0-9]{1,3}\.){3}[0-9]{1,3}$' >/dev/null && {
        local IFS=.
        set -- $1
        [[ $1 -le 255 && $2 -le 255 && $3 -le 255 && $4 -le 255 ]]
    }
}

# Create cache directory if it doesn't exist
mkdir -p "$CACHEDIR" || {
    echo "Error: Unable to create cache directory"
    exit 1
}

# Get WAN IP with timeout and retry
wanIP=$(curl -s -m 10 --retry 3 ifconfig.co) || {
    echo "Error: Failed to obtain WAN IP"
    exit 1
}

# Validate IP address
if ! validate_ip "$wanIP"; then
    echo "Some error occurred obtaining WAN IP."
    exit 1
fi

# Check if LastIP exists, else set to empty string
if defaults read "$prefs" LastIP &>/dev/null; then
    lastWanIP=$(defaults read "$prefs" LastIP)
else
    lastWanIP=""
fi

if [ "$wanIP" = "$lastWanIP" ]; then
    echo "No change in IP address"
else
    defaults write "$prefs" LastIP "$wanIP"
    cmd="https://ipinfo.io/$wanIP?token=$token"
    json_data=$(curl -s -m 10 --retry 3 "$cmd") || {
        echo "Error: Failed to fetch location data"
        exit 1
    }

    # Assign values to variables
    IP=$(extract_value "$json_data" "ip")

    # Validate IP address
    if ! validate_ip "$IP"; then
        echo "Some error occurred obtaining location."
        exit 1
    fi

    HOSTNAME=$(extract_value "$json_data" "hostname")
    CITY=$(extract_value "$json_data" "city")
    REGION=$(extract_value "$json_data" "region")
    COUNTRY=$(extract_value "$json_data" "country")
    LOCATION=$(extract_value "$json_data" "loc")
    ORGANIZATION=$(extract_value "$json_data" "org")
    POSTAL_CODE=$(extract_value "$json_data" "postal")
    TIMEZONE=$(extract_value "$json_data" "timezone")

    # Clean empty values
    clean_value() {
        [ -z "$1" ] && echo "N/A" || echo "$1"
    }

    # Output data with timestamp
    {
        echo "timestamp${SEPARATOR}$(date '+%Y-%m-%d %H:%M:%S')"
        echo "ip${SEPARATOR}${IP}"
        echo "hostname${SEPARATOR}$(clean_value "${HOSTNAME}")"
        echo "city${SEPARATOR}$(clean_value "${CITY}")"
        echo "region${SEPARATOR}$(clean_value "${REGION}")"
        echo "country${SEPARATOR}$(clean_value "${COUNTRY}")"
        echo "location${SEPARATOR}$(clean_value "${LOCATION}")"
        echo "organization${SEPARATOR}$(clean_value "${ORGANIZATION}")"
        echo "postal_code${SEPARATOR}$(clean_value "${POSTAL_CODE}")"
        echo "timezone${SEPARATOR}$(clean_value "${TIMEZONE}")"
    } >"${OUTPUT_FILE}"

    echo "Location data updated successfully"
fi
