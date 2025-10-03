#!/bin/bash

# Script to automatically generate interface-repository bindings
# and update RepositoryServiceProvider.php
# This script will scan the app/Interfaces and app/Repositories directories
# and generate the appropriate bindings in RepositoryServiceProvider.php

get_root_path() {
  if [[ -n "$PROJECT_ROOT" ]]; then
    echo "$PROJECT_ROOT"
  else
    # Default: get parent directory of script (assuming script is in auto_scripts/auto_gen)
    cd "$(dirname "$0")/../.." && pwd
  fi
}

ROOT_PATH=$(get_root_path)
PROVIDER_FILE="$ROOT_PATH/app/Providers/RepositoryServiceProvider.php"

# Check if the provider file exists
if [[ ! -f "$PROVIDER_FILE" ]]; then
  echo "Error: RepositoryServiceProvider.php not found at $PROVIDER_FILE"
  exit 1
fi

echo "Generating repository bindings..."

# Create temp file for the new provider content
TEMP_FILE=$(mktemp)

# Generate the import section
echo "<?php

declare(strict_types=1);

namespace App\Providers;
" > "$TEMP_FILE"

# Find all interface files and generate use statements
echo "# Finding interfaces..."
find "$ROOT_PATH/app/Interfaces" -type f -name "*Interface.php" | sort | while read -r interface_file; do
  # Extract the namespace and interface name
  interface_path=${interface_file#"$ROOT_PATH/app/"}
  interface_path=${interface_path%.php}
  interface_path=${interface_path//\//"\\"}
  echo "use App\\$interface_path;" >> "$TEMP_FILE"
done

# Find all repository files and generate use statements
echo "# Finding repositories..."
find "$ROOT_PATH/app/Repositories" -type f -name "*Repository.php" | sort | while read -r repo_file; do
  # Extract the namespace and repository name
  repo_path=${repo_file#"$ROOT_PATH/app/"}
  repo_path=${repo_path%.php}
  repo_path=${repo_path//\//"\\"}
  echo "use App\\$repo_path;" >> "$TEMP_FILE"
done

# Add ServiceProvider import
echo "use Illuminate\Support\ServiceProvider;" >> "$TEMP_FILE"

# Start the class definition
echo "
final class RepositoryServiceProvider extends ServiceProvider
{
    public \$bindings = [" >> "$TEMP_FILE"

# Generate the Master bindings
echo "        // Master" >> "$TEMP_FILE"
find "$ROOT_PATH/app/Interfaces/Master" -type f -name "*Interface.php" | sort | while read -r interface_file; do
  interface_name=$(basename "$interface_file" .php)
  repo_name="${interface_name/Interface/Repository}"
  
  # Check if the repository file exists
  repo_file="$ROOT_PATH/app/Repositories/Master/${repo_name}.php"
  if [[ -f "$repo_file" ]]; then
    echo "        $interface_name::class => $repo_name::class," >> "$TEMP_FILE"
  else
    echo "Warning: Repository file for $interface_name not found at $repo_file"
  fi
done

# Generate the Management bindings
echo "
        // Management" >> "$TEMP_FILE"
find "$ROOT_PATH/app/Interfaces/Management" -type f -name "*Interface.php" | sort | while read -r interface_file; do
  interface_name=$(basename "$interface_file" .php)
  repo_name="${interface_name/Interface/Repository}"
  
  # Check if the repository file exists
  repo_file="$ROOT_PATH/app/Repositories/Management/${repo_name}.php"
  if [[ -f "$repo_file" ]]; then
    echo "        $interface_name::class => $repo_name::class," >> "$TEMP_FILE"
  else
    echo "Warning: Repository file for $interface_name not found at $repo_file"
  fi
done

# Generate the Master History bindings
echo "
        // Master History" >> "$TEMP_FILE"
find "$ROOT_PATH/app/Interfaces/History/Master" -type f -name "*Interface.php" | sort | while read -r interface_file; do
  interface_name=$(basename "$interface_file" .php)
  repo_name="${interface_name/Interface/Repository}"
  
  # Check if the repository file exists
  repo_file="$ROOT_PATH/app/Repositories/History/Master/${repo_name}.php"
  if [[ -f "$repo_file" ]]; then
    echo "        $interface_name::class => $repo_name::class," >> "$TEMP_FILE"
  else
    echo "Warning: Repository file for $interface_name not found at $repo_file"
  fi
done

# Generate the Management History bindings
echo "
        // Management History" >> "$TEMP_FILE"
find "$ROOT_PATH/app/Interfaces/History/Management" -type f -name "*Interface.php" | sort | while read -r interface_file; do
  interface_name=$(basename "$interface_file" .php)
  repo_name="${interface_name/Interface/Repository}"
  
  # Check if the repository file exists
  repo_file="$ROOT_PATH/app/Repositories/History/Management/${repo_name}.php"
  if [[ -f "$repo_file" ]]; then
    echo "        $interface_name::class => $repo_name::class," >> "$TEMP_FILE"
  else
    echo "Warning: Repository file for $interface_name not found at $repo_file"
  fi
done

# Close the bindings array and add the rest of the class
echo "    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach (\$this->bindings as \$repositoryInterface => \$repository) {
            \$this->app->bind(\$repositoryInterface, \$repository);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}" >> "$TEMP_FILE"

# Replace the original file with our generated one
mv "$TEMP_FILE" "$PROVIDER_FILE"

echo "Repository bindings updated successfully!"