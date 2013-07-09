#!/bin/bash
echo "Running PHPUnit Tests..."
echo "========================"
echo

vendor/bin/phpunit --configuration phpunit.xml --coverage-html coverage

echo
echo "Creating phpmd reports..."
echo "========================="
echo

vendor/bin/phpmd ./ html codesize,controversial,design,naming,unusedcode --reportfile phpmd/report.html --exclude vendor,tests,coverage,schema

echo
echo "Completed! Have a nice day!"
echo