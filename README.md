# /!\ DEPRECATED /!\

This follows our old norm, do not use it of your using PrestaShop 1.6.1.0+

Since version 1.6.1.0 of PrestaShop, we are using the [PSR-2 Coding Style Guide](http://www.php-fig.org/psr/psr-2/) as our coding standards.

All PrestaShop Coding Standards have been [introduced here](http://build.prestashop.com/news/prestashop-coding-standards/).

### Installation

1. Install phpcs:
        pear install PHP_CodeSniffer

2. Find your PEAR directory:
        pear config-show | grep php_dir

3. You can run PHP CodeSniffer against an entire directory:
        phpcs --standard=PSR2 PrestaShop/classes/
