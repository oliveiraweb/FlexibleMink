#!/usr/bin/env bash

set -euo pipefail

root="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/../../ && pwd )"

# Associative array mapping Docker Volume names to paths
declare -A VOLUMES
VOLUMES["flexible_mink_project"]="$root"
export VOLUMES

readonly DIRECTORY=0
readonly FILE=1

# Associative array mapping Docker Volume targets to types
declare -A VOLUME_TYPES
VOLUME_TYPES["flexible_mink_project"]=$DIRECTORY
export VOLUME_TYPES

export PROJECT_VOLUME="flexible_mink_project"
