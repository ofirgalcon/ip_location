#!/bin/bash

# Remove ip_location script
rm -f "${MUNKIPATH}preflight.d/ip_location.sh"

# Remove ip_location.txt file
rm -f "${MUNKIPATH}preflight.d/cache/ip_location.txt"
