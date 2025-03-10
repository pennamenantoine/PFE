#!/bin/bash

# Set default server host (can be overridden)
SERVER_HOST="localhost"

# Allow passing a custom host via script argument
if [[ -n "$1" ]]; then
    SERVER_HOST="$1"
fi

# If the server is running on an external IP, update DB_HOST
if [[ "$SERVER_HOST" != "localhost" && "$SERVER_HOST" != "127.0.0.1" ]]; then
    export DB_HOST="$SERVER_HOST"
    echo "DB_HOST updated to $SERVER_HOST"
fi

# Start PHP server with custom host
echo "Starting PHP server on $SERVER_HOST..."
php -S "$SERVER_HOST":8000 -t  ../website/ router.php &
PHP_PID=$!  # Capture PHP server process ID

# Function to stop PHP server and clean environment
cleanup() {
    echo "Stopping PHP server..."
    kill "$PHP_PID" 2>/dev/null
    echo "Reset DB_HOST to localhost"
    export DB_HOST="localhost"
    # Remove session files (if stored in a custom folder)
    SESSION_DIR="../logs/sessions"
    if [ -d "$SESSION_DIR" ]; then
        echo "Deleting session files..."
        rm -f "$SESSION_DIR"/*
    fi
    echo "Cleanup complete."
    exit 0
}

# Trap CTRL+C (SIGINT) to trigger cleanup
trap cleanup SIGINT


# Keep script running until manually stopped
wait "$PHP_PID"
cleanup
