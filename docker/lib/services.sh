#!/usr/bin/env bash

set -euo pipefail

# Associative array mapping service names to host names
declare -A SERVICE_HOST_MAP
SERVICE_HOST_MAP["web"]=web
SERVICE_HOST_MAP["chrome"]=chrome
export SERVICE_HOST_MAP

# Associative array mapping service names to TCP ports
declare -A SERVICE_PORT_MAP
SERVICE_PORT_MAP["web"]=80
SERVICE_PORT_MAP["chrome"]=4444
export SERVICE_PORT_MAP
