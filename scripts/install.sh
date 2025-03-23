#!/bin/bash

# ip_location controller
CTL="${BASEURL}index.php?/module/ip_location/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ip_location.sh" -o "${MUNKIPATH}preflight.d/ip_location.sh"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ip_location.sh"

	# Set preference to include this file in the preflight check
	setreportpref "ip_location" "${CACHEPATH}ip_location.txt"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ip_location.sh"

	# Signal that we had an error
	ERR=1
fi
