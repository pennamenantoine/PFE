#!/bin/bash

# Define Environment Variables
DB_HOST="127.0.0.1"
DB_USER="root"
DB_PASS="toor"
DB_NAME="user_auth"

# Detect OS
OS_TYPE=$(uname)

if [[ "$OS_TYPE" == "Linux" || "$OS_TYPE" == "Darwin" ]]; then
    echo "Setting environment variables for Linux/macOS..."

    # Export variables for current session
    export DB_HOST="$DB_HOST"
    export DB_USER="$DB_USER"
    export DB_PASS="$DB_PASS"
    export DB_NAME="$DB_NAME"

    # Detect shell (bash or zsh)
    if [[ "$SHELL" == *"zsh"* ]]; then
        PROFILE_FILE="$HOME/.zshrc"
    else
        PROFILE_FILE="$HOME/.bashrc"
    fi

    # Add to profile only if not already present
	if ! grep -q '^export DB_HOST=' "$PROFILE_FILE"; then
    		echo "export DB_HOST=\"$DB_HOST\"" >> "$PROFILE_FILE"
	fi
	if ! grep -q '^export DB_USER=' "$PROFILE_FILE"; then
    		echo "export DB_USER=\"$DB_USER\"" >> "$PROFILE_FILE"
	fi
	if ! grep -q '^export DB_PASS=' "$PROFILE_FILE"; then
    		echo "export DB_PASS=\"$DB_PASS\"" >> "$PROFILE_FILE"
	fi
	if ! grep -q '^export DB_NAME=' "$PROFILE_FILE"; then
    		echo "export DB_NAME=\"$DB_NAME\"" >> "$PROFILE_FILE"
	fi

    # Apply changes immediately
    source "$PROFILE_FILE"

elif [[ "$OS_TYPE" == "MINGW"* || "$OS_TYPE" == "CYGWIN"* ]]; then
    echo "Setting environment variables for Windows (Git Bash/WSL)..."

    # Set variables for current session
    export DB_HOST="$DB_HOST"
    export DB_USER="$DB_USER"
    export DB_PASS="$DB_PASS"
    export DB_NAME="$DB_NAME"

    # Persist variables in Windows User environment (not Machine to avoid logout requirement)
    powershell.exe -Command "[System.Environment]::SetEnvironmentVariable('DB_HOST', '$DB_HOST', 'User')"
    powershell.exe -Command "[System.Environment]::SetEnvironmentVariable('DB_USER', '$DB_USER', 'User')"
    powershell.exe -Command "[System.Environment]::SetEnvironmentVariable('DB_PASS', '$DB_PASS', 'User')"
    powershell.exe -Command "[System.Environment]::SetEnvironmentVariable('DB_NAME', '$DB_NAME', 'User')"

else
    echo "Unsupported OS: $OS_TYPE"
    exit 1
fi

echo "Environment variables set successfully!"

# Help message at the end
echo ""
echo "To run this script: "
echo "  1- Make it executable: chmod +x set_env.sh"
echo "  2- Run it: ./set_env.sh"
echo "To apply changes: "
echo "   - Linux/macOS: Open a new terminal or run: source ~/.bashrc or source ~/.zshrc"
echo "   - Windows: Restart the terminal or open a new one."
echo ""
