#!/bin/bash
echo "Running PHPUnit Tests..."
echo "========================"
echo

mkdir coverate
vendor/bin/phpunit --configuration phpunit.xml --coverage-html coverage

echo
echo "Creating phpmd reports..."
echo "========================="
echo

mkdir phpmd
vendor/bin/phpmd ./ html rulesets/codesize.xml --reportfile phpmd/codesize.html --exclude vendor,tests,coverage,schema
vendor/bin/phpmd ./ html rulesets/controversial.xml --reportfile phpmd/controversial.html --exclude vendor,tests,coverage,schema
vendor/bin/phpmd ./ html rulesets/design.xml --reportfile phpmd/design.html --exclude vendor,tests,coverage,schema
vendor/bin/phpmd ./ html rulesets/naming.xml --reportfile phpmd/naming.html --exclude vendor,tests,coverage,schema
vendor/bin/phpmd ./ html rulesets/unusedcode.xml --reportfile phpmd/unusedcode.html --exclude vendor,tests,coverage,schema

echo
echo "Completed! Have a nice day!"
echo