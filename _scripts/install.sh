#!/bin/bash
set -x # Show the output of the following commands (useful for debugging)

# Import the SSH deployment key
openssl aes-256-cbc -K $encrypted_b6b3065e2137_key -iv $encrypted_b6b3065e2137_iv -in deploy-key.enc -out deploy-key -d
rm deploy-key.enc # Don't need it anymore
chmod 600 deploy-key
eval `ssh-agent -s` #start shh agent
ssh-add ~/.ssh/id_rsa