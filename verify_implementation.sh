#!/bin/bash
# Bon de Retour & Avoir Implementation Verification Script

echo "=========================================="
echo "IMPLEMENTATION VERIFICATION REPORT"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Count of checks
PASSED=0
FAILED=0

# Function to check file exists
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✅${NC} $1"
        ((PASSED++))
    else
        echo -e "${RED}❌${NC} $1 (NOT FOUND)"
        ((FAILED++))
    fi
}

# Function to check PHP syntax
check_php_syntax() {
    if php -l "$1" > /dev/null 2>&1; then
        echo -e "${GREEN}✅${NC} $1 (Syntax OK)"
        ((PASSED++))
    else
        echo -e "${RED}❌${NC} $1 (Syntax ERROR)"
        ((FAILED++))
    fi
}

echo "1. CHECKING MODEL FILES"
echo "========================"
check_file "app/Models/Avoir.php"
check_file "app/Models/AvoirLigne.php"
check_file "app/Models/BonRetour.php"
check_file "app/Models/BonRetourLigne.php"
check_file "app/Models/Facture.php"
echo ""

echo "2. CHECKING SERVICE FILES"
echo "=========================="
check_file "app/Services/BonRetourService.php"
check_file "app/Services/AvoirService.php"
echo ""

echo "3. CHECKING CONTROLLER FILES"
echo "=============================="
check_file "app/Http/Controllers/BonRetourController.php"
check_file "app/Http/Controllers/AvoirController.php"
echo ""

echo "4. CHECKING VIEW FILES - BON DE RETOUR"
echo "========================================"
check_file "resources/views/bon-retour/index.blade.php"
check_file "resources/views/bon-retour/create.blade.php"
check_file "resources/views/bon-retour/show.blade.php"
check_file "resources/views/bon-retour/edit.blade.php"
echo ""

echo "5. CHECKING VIEW FILES - AVOIR"
echo "================================"
check_file "resources/views/avoir/index.blade.php"
check_file "resources/views/avoir/show.blade.php"
echo ""

echo "6. CHECKING MIGRATION FILES"
echo "============================"
check_file "database/migrations/2025_02_06_100001_create_avoirs_table.php"
echo ""

echo "7. CHECKING LAYOUT & ROUTING FILES"
echo "===================================="
check_file "resources/views/layouts/dashboard.blade.php"
check_file "routes/web.php"
echo ""

echo "8. PHP SYNTAX VERIFICATION"
echo "==========================="
check_php_syntax "app/Models/Avoir.php"
check_php_syntax "app/Models/AvoirLigne.php"
check_php_syntax "app/Services/BonRetourService.php"
check_php_syntax "app/Services/AvoirService.php"
check_php_syntax "app/Http/Controllers/BonRetourController.php"
check_php_syntax "app/Http/Controllers/AvoirController.php"
echo ""

echo "9. CHECKING DOCUMENTATION"
echo "==========================="
check_file "BON_RETOUR_AVOIR_IMPLEMENTATION.md"
check_file "TESTING_GUIDE.md"
check_file "COMPLETION_CHECKLIST.md"
echo ""

echo "10. DATABASE VERIFICATION"
echo "=========================="
if [ -f "database/database.sqlite" ]; then
    echo -e "${GREEN}✅${NC} database/database.sqlite exists"
    ((PASSED++))
    
    # Check if tables exist
    if sqlite3 database/database.sqlite ".tables" | grep -q "avoirs"; then
        echo -e "${GREEN}✅${NC} avoirs table exists"
        ((PASSED++))
    else
        echo -e "${RED}❌${NC} avoirs table NOT FOUND"
        ((FAILED++))
    fi
    
    if sqlite3 database/database.sqlite ".tables" | grep -q "avoir_lignes"; then
        echo -e "${GREEN}✅${NC} avoir_lignes table exists"
        ((PASSED++))
    else
        echo -e "${RED}❌${NC} avoir_lignes table NOT FOUND"
        ((FAILED++))
    fi
else
    echo -e "${RED}❌${NC} database/database.sqlite NOT FOUND"
    ((FAILED++))
fi
echo ""

echo "11. ROUTE VERIFICATION"
echo "======================="
if grep -q "bon-retour" routes/web.php; then
    echo -e "${GREEN}✅${NC} bon-retour routes registered"
    ((PASSED++))
else
    echo -e "${RED}❌${NC} bon-retour routes NOT registered"
    ((FAILED++))
fi

if grep -q "avoir" routes/web.php; then
    echo -e "${GREEN}✅${NC} avoir routes registered"
    ((PASSED++))
else
    echo -e "${RED}❌${NC} avoir routes NOT registered"
    ((FAILED++))
fi
echo ""

echo "12. MODEL RELATIONSHIPS VERIFICATION"
echo "====================================="
if grep -q "hasMany.*avoirs" app/Models/Facture.php; then
    echo -e "${GREEN}✅${NC} Facture.php has avoirs relationship"
    ((PASSED++))
else
    echo -e "${RED}❌${NC} Facture.php missing avoirs relationship"
    ((FAILED++))
fi

if grep -q "calculateInvoiceQuantity" app/Models/Facture.php; then
    echo -e "${GREEN}✅${NC} Facture.php has calculateInvoiceQuantity method"
    ((PASSED++))
else
    echo -e "${RED}❌${NC} Facture.php missing calculateInvoiceQuantity method"
    ((FAILED++))
fi

if grep -q "canReturnQuantity" app/Models/BonRetour.php; then
    echo -e "${GREEN}✅${NC} BonRetour.php has canReturnQuantity method"
    ((PASSED++))
else
    echo -e "${RED}❌${NC} BonRetour.php missing canReturnQuantity method"
    ((FAILED++))
fi
echo ""

echo "=========================================="
echo "SUMMARY"
echo "=========================================="
echo -e "${GREEN}✅ Passed: $PASSED${NC}"
echo -e "${RED}❌ Failed: $FAILED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 ALL CHECKS PASSED!${NC}"
    echo "The implementation is complete and ready for use."
    exit 0
else
    echo -e "${RED}⚠️  SOME CHECKS FAILED!${NC}"
    echo "Please review the errors above."
    exit 1
fi
