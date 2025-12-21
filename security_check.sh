#!/bin/bash

# ============================================
# DEEPEYES SECURITY CHECK SCRIPT
# Run: chmod +x security_check.sh && ./security_check.sh
# ============================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "============================================"
echo "  DEEPEYES SECURITY CHECK"
echo "============================================"
echo ""

ERRORS=0
WARNINGS=0

# Check .env permissions
echo -n "Checking .env permissions... "
if [ -f ".env" ]; then
    PERMS=$(stat -c %a .env 2>/dev/null || stat -f %A .env 2>/dev/null)
    if [ "$PERMS" = "600" ] || [ "$PERMS" = "640" ]; then
        echo -e "${GREEN}OK${NC} (${PERMS})"
    else
        echo -e "${RED}FAIL${NC} - Should be 600 or 640, is ${PERMS}"
        ((ERRORS++))
    fi
else
    echo -e "${YELLOW}SKIP${NC} - .env not found"
fi

# Check storage permissions
echo -n "Checking storage directory... "
if [ -d "storage" ]; then
    if [ -w "storage" ]; then
        echo -e "${GREEN}OK${NC} (writable)"
    else
        echo -e "${RED}FAIL${NC} - Not writable"
        ((ERRORS++))
    fi
else
    echo -e "${RED}FAIL${NC} - Directory not found"
    ((ERRORS++))
fi

# Check if debug is disabled in production
echo -n "Checking APP_DEBUG... "
if [ -f ".env" ]; then
    DEBUG=$(grep "^APP_DEBUG=" .env | cut -d'=' -f2)
    if [ "$DEBUG" = "false" ]; then
        echo -e "${GREEN}OK${NC} (disabled)"
    else
        echo -e "${RED}FAIL${NC} - Debug is enabled!"
        ((ERRORS++))
    fi
else
    echo -e "${YELLOW}SKIP${NC}"
fi

# Check APP_ENV
echo -n "Checking APP_ENV... "
if [ -f ".env" ]; then
    ENV=$(grep "^APP_ENV=" .env | cut -d'=' -f2)
    if [ "$ENV" = "production" ]; then
        echo -e "${GREEN}OK${NC} (production)"
    else
        echo -e "${YELLOW}WARNING${NC} - Not in production mode (${ENV})"
        ((WARNINGS++))
    fi
else
    echo -e "${YELLOW}SKIP${NC}"
fi

# Check .htaccess exists
echo -n "Checking public/.htaccess... "
if [ -f "public/.htaccess" ]; then
    echo -e "${GREEN}OK${NC}"
else
    echo -e "${RED}FAIL${NC} - File not found"
    ((ERRORS++))
fi

# Check root .htaccess
echo -n "Checking root .htaccess... "
if [ -f ".htaccess" ]; then
    echo -e "${GREEN}OK${NC}"
else
    echo -e "${YELLOW}WARNING${NC} - File not found (recommended for Apache)"
    ((WARNINGS++))
fi

# Check if vendor is not in public
echo -n "Checking vendor not exposed... "
if [ -d "public/vendor" ]; then
    echo -e "${RED}FAIL${NC} - vendor directory in public!"
    ((ERRORS++))
else
    echo -e "${GREEN}OK${NC}"
fi

# Check if .git is not in public
echo -n "Checking .git not exposed... "
if [ -d "public/.git" ]; then
    echo -e "${RED}FAIL${NC} - .git directory in public!"
    ((ERRORS++))
else
    echo -e "${GREEN}OK${NC}"
fi

# Check storage link
echo -n "Checking storage link... "
if [ -L "public/storage" ]; then
    echo -e "${GREEN}OK${NC}"
else
    echo -e "${YELLOW}WARNING${NC} - Storage not linked (run: php artisan storage:link)"
    ((WARNINGS++))
fi

# Check for exposed sensitive files via curl (if server is running)
echo ""
echo "Testing HTTP access to sensitive files..."

BASE_URL="https://deepeyes.online"

test_blocked() {
    local path=$1
    local response=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}${path}" 2>/dev/null)
    echo -n "  ${path}... "
    if [ "$response" = "404" ] || [ "$response" = "403" ]; then
        echo -e "${GREEN}BLOCKED${NC} (${response})"
    elif [ "$response" = "000" ]; then
        echo -e "${YELLOW}SKIP${NC} (server not reachable)"
    else
        echo -e "${RED}EXPOSED!${NC} (${response})"
        ((ERRORS++))
    fi
}

test_blocked "/.env"
test_blocked "/.git/config"
test_blocked "/artisan"
test_blocked "/composer.json"
test_blocked "/config/app.php"
test_blocked "/storage/logs/laravel.log"

echo ""
echo "============================================"
echo "  RESULTS"
echo "============================================"
echo -e "Errors:   ${RED}${ERRORS}${NC}"
echo -e "Warnings: ${YELLOW}${WARNINGS}${NC}"
echo ""

if [ $ERRORS -gt 0 ]; then
    echo -e "${RED}SECURITY CHECK FAILED!${NC}"
    echo "Please fix the errors above before deploying."
    exit 1
else
    echo -e "${GREEN}SECURITY CHECK PASSED!${NC}"
    exit 0
fi
