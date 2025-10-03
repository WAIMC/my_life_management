#!/bin/sh

# This script runs all generation scripts in the correct order
# It is designed to be called from Laravel's Artisan command

# Get the absolute path to the project root
PROJECT_ROOT="$1"
if [ -z "$PROJECT_ROOT" ]; then
    # If PROJECT_ROOT is not provided, determine it automatically
    SCRIPT_DIR=$(dirname "$0")
    PROJECT_ROOT=$(cd "$SCRIPT_DIR/.." && pwd)
    echo "PROJECT_ROOT not provided, using: $PROJECT_ROOT"
fi

# Output debug information
echo "PROJECT_ROOT: $PROJECT_ROOT"
echo "Current directory: $(pwd)"
echo "Script location: $0"

# Set script directory
SCRIPTS_DIR="$PROJECT_ROOT/auto_script/auto_gen"

# Verify scripts directory exists
if [ ! -d "$SCRIPTS_DIR" ]; then
    echo "Error: Scripts directory not found: $SCRIPTS_DIR"
    exit 1
fi

echo "Scripts directory: $SCRIPTS_DIR"
echo "Contents of scripts directory:"
ls -la "$SCRIPTS_DIR"

# Make sure all scripts are executable
chmod +x "$SCRIPTS_DIR"/*.sh

# Export PROJECT_ROOT for all scripts to use
export PROJECT_ROOT

# Run each generation script
cd "$PROJECT_ROOT"

# Check if bash is available
if command -v bash >/dev/null 2>&1; then
    SHELL_CMD="bash"
else
    SHELL_CMD="sh"
fi
echo "Using shell: $SHELL_CMD"

# Create symbolic link for schema.json if necessary
DATA_SCHEMA_DIR="$PROJECT_ROOT/auto_script/dataSchema"
if [ -d "$DATA_SCHEMA_DIR" ] && [ -f "$DATA_SCHEMA_DIR/schema.json" ]; then
    echo "Schema file found at: $DATA_SCHEMA_DIR/schema.json"
    
    # Create auto_scripts symbolic link for scripts expecting that path
    if [ ! -d "$PROJECT_ROOT/auto_scripts" ]; then
        echo "Creating symbolic link for auto_scripts -> auto_script"
        ln -sf "$PROJECT_ROOT/auto_script" "$PROJECT_ROOT/auto_scripts"
    fi
else
    echo "ERROR: Schema file not found at: $DATA_SCHEMA_DIR/schema.json"
    exit 1
fi

# Fix script paths in the scripts that might reference the wrong path
for script_file in "$SCRIPTS_DIR"/*.sh; do
    echo "Checking script: $script_file"
    # Replace auto_scripts with auto_script in all scripts
    sed -i 's|auto_scripts/dataSchema|auto_script/dataSchema|g' "$script_file" || true
done

# Run Model generator
echo "Running Model Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_model.sh"

# Run Repository/Interface generator
echo "Running Repository/Interface Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_repository_interface.sh"

# Run Service generator
echo "Running Service Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_service.sh"

# Run Resource generator
echo "Running Resource Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_resource.sh"

# Run Validation generator
echo "Running Validation Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_validate.sh"

# Run Controller generator
echo "Running Controller Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_controller.sh"

# Run Route generator
echo "Running Route Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_route.sh"

# Run Repository Bindings generator
echo "Running Repository Bindings Generator..."
$SHELL_CMD "$SCRIPTS_DIR/generate_repository_binding.sh"

echo "All generation scripts completed"