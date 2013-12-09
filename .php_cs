<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('LICENSE')
    ->notName('LICENSE.txt')
    ->notName('README.md')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('phpunit.xml*')
    ->notName('*.phar')
    ->exclude('vendor')
    ->exclude('Symfony/CS/Tests/Fixer')
    ->notName('ElseifFixer.php')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;
