IP Location Module
==============

Provides IP location information for clients, including geographical and organizational data. The module collects location data when the client's WAN IP address changes and displays it in a user-friendly format with relative timestamps showing how long ago the IP address last changed.

Data can be viewed under the IP Location tab on the client details page or using the IP Location list view.

Installation
---

1. Copy the module to your MunkiReport modules directory:
   ```bash
   cp -r ip_location /path/to/munkireport/local/modules/
   ```

2. Run the database migration:
   ```bash
   php /path/to/munkireport/index.php migrate
   ```

Configuration
---

This module uses the [ipinfo.io](https://ipinfo.io/) API to gather location data. You will need to:

1. Register for an API token at <https://ipinfo.io/>
2. Create a plist file at `/Library/Preferences/com.gal.iplocation.plist` with your token:

   ```bash
   sudo defaults write /Library/Preferences/com.gal.iplocation.plist Token "your_token_here"
   ```

3. Deploy the script to your managed clients:
   ```bash
   # Copy the script to your Munki repository
   cp ip_location.sh /path/to/munki/repo/scripts/
   ```

4. Add the script to your Munki manifests or use a management tool to deploy it.

How It Works
---

The module works by:

1. **IP Detection**: The script detects the client's current WAN IP address
2. **Change Detection**: Only fetches new location data when the IP address changes
3. **Data Collection**: Uses the ipinfo.io API to get geographical and organizational data
4. **Caching**: Stores the last known IP to avoid unnecessary API calls
5. **Reporting**: Sends the data to MunkiReport for display

The script includes intelligent caching to minimize API calls and only updates location data when the client's IP address actually changes.

Table Schema
---

* id - int - Primary key
* serial_number - varchar(255) - Unique client identifier
* ip - varchar(255) - IP address
* hostname - varchar(255) - Hostname
* city - varchar(255) - City
* region - varchar(255) - Region/State/Province
* country - varchar(255) - Country
* location - varchar(255) - Geographical location coordinates
* organization - varchar(255) - Organization/ISP
* postal_code - varchar(255) - Postal/ZIP code
* timezone - varchar(255) - Timezone
* last_update - bigInteger - Unix timestamp showing when the IP address last changed (formatted as relative time in listings)

Features
---

* **Smart Caching**: Only updates when IP address changes
* **Error Handling**: Robust error handling for network issues
* **Data Validation**: Validates IP addresses and API responses
* **Relative Timestamps**: Displays "time since last IP change" in listing views
* **Comprehensive Data**: Includes geographical, organizational, and timezone information

Widgets
---

This module provides the following widgets:

* IP Location City
* IP Location Region
* IP Location Country
* IP Location Organization
* IP Location Postal Code
* IP Location Timezone

Usage
---

The module will automatically collect data when:

1. The script runs and detects a new IP address
2. Force refresh is enabled via `FORCE_REFRESH=1` environment variable
3. The client's WAN IP address changes

Data is displayed in the MunkiReport interface with:
- Relative timestamps showing how long ago the IP address last changed (e.g., "2 hours ago", "3 days ago")
- Full date/time available as tooltips
- Organized geographical and organizational information

Troubleshooting
---

**Script not running**: Ensure the script has execute permissions and is properly deployed to clients.

**No data appearing**: Check that the API token is correctly configured and the script has network access.

**API errors**: Verify your ipinfo.io token is valid and has sufficient quota.

**Permission errors**: The script needs to write to `/Library/Preferences/com.gal.iplocation.plist` for caching.

Testing
---

You can test the script manually:

```bash
# Test with a specific IP
FORCE_REFRESH=1 wanIP=8.8.8.8 ./ip_location.sh

# Check the output
cat cache/ip_location.txt
```

The script will output location data in the format expected by MunkiReport.
