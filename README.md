PrestaShop-norm-validator
=========================

Since version 1.6.1.0 of PrestaShop, we are using the [PSR-2 Coding Style Guide](http://www.php-fig.org/psr/psr-2/) as our coding standards. [Learn more about this here](http://build.prestashop.com/news/prestashop-moves-to-psr-2/).

Installation
------------

1. Install phpcs:
        pear install PHP_CodeSniffer

2. Find your PEAR directory:
        pear config-show | grep php_dir

3. You can run PHP CodeSniffer against an entire directory:
        phpcs --standard=PSR2 PrestaShop/classes/
