#!/bin/bash

# Laravel Referral App - Deployment Preparation Script
# Run this locally before deploying

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Preparing Laravel Referral App for Deployment${NC}"

# Check if we're in the Laravel project directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: This script must be run from the Laravel project root directory${NC}"
    exit 1
fi

# Generate APP_KEY if not exists
echo -e "${GREEN}🔑 Generating Laravel APP_KEY...${NC}"
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Generate key and capture it
APP_KEY=$(php artisan key:generate --show)
echo -e "${YELLOW}Generated APP_KEY: $APP_KEY${NC}"

# Update .env.production with the generated key
if [ -f ".env.production" ]; then
    # Escape special characters in APP_KEY for sed
    ESCAPED_KEY=$(echo "$APP_KEY" | sed 's/[[\.*^$()+?{|]/\\&/g')
    sed -i.bak "s|APP_KEY=.*|APP_KEY=$ESCAPED_KEY|" .env.production
    echo -e "${GREEN}✅ Updated .env.production with new APP_KEY${NC}"
else
    echo -e "${RED}❌ .env.production file not found${NC}"
    exit 1
fi

# Install dependencies
echo -e "${GREEN}📦 Installing Composer dependencies...${NC}"
composer install

echo -e "${GREEN}📦 Installing NPM dependencies...${NC}"
npm install

# Build assets
echo -e "${GREEN}🏗️  Building assets...${NC}"
npm run build

# Run tests
echo -e "${GREEN}🧪 Running tests...${NC}"
php artisan test --stop-on-failure

echo -e "${GREEN}✅ Deployment preparation completed!${NC}"
echo -e "${YELLOW}📋 Next steps:${NC}"
echo -e "1. Copy the contents of .env.production to your GitHub Secrets as ENV_FILE"
echo -e "2. Make sure you have set up your VPS using scripts/server-setup.sh"
echo -e "3. Configure GitHub Secrets (HOST, USERNAME, SSH_PRIVATE_KEY, ENV_FILE)"
echo -e "4. Push to main branch to trigger deployment"

echo -e "\n${GREEN}GitHub Secrets Configuration:${NC}"
echo -e "HOST: 138.197.67.195"
echo -e "USERNAME: deploy"
echo -e "SSH_PRIVATE_KEY: (from /home/deploy/deployment-info.txt on your VPS)"
echo -e "ENV_FILE: (contents of .env.production file)"

echo -e "\n${YELLOW}Your .env.production file is ready with APP_KEY: $APP_KEY${NC}"
