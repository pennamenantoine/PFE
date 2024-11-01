#!/bin/bash

# Set variables
COMMIT_MESSAGE=${1:-"Update via script"}

# Check for any changes
if [[ `git status --porcelain` ]]; then
  echo "Changes detected, proceeding with git operations."

  # Stage changes
  git add .

  # Commit changes
  git commit -m "$COMMIT_MESSAGE"

  # Push changes
  git push origin main  # Change 'main' to the branch name you're working with

  echo "Changes pushed to the repository."

else
  echo "No changes to commit."
fi

