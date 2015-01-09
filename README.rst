========================
PrestaShopCodingStandard
========================

Installation
============

There are several ways to get the standard, which I am going to describe now. There is no right or wrong. Which way you choose depends on your preferences and at least on your requirements.

Composer
--------

**This is the recommended way to install the standard.**

In case you don't installed PHP_CodeSniffer yet - no problem. This package is marked as dependencies of this standard and will install automatically.

System-wide
"""""""""""

::

        $ composer global require "Konafets/PrestaShopCodingStandard": "dev-master" ```

Make sure you have ~/.composer/vendor/bin/ in your PATH.

Project-wide
""""""""""""

Create a composer.json in the root folder of your project and declare this standard as a dependency:

::

        {
                "require": {
                        "Konafets/PrestaShopCodingStandard": "~1.0"
                }
        }

This will install latest version from the 1.0 branch of this standard. If you live on the edge, try:

::

        {
                "require": {
                        "Konafets/PrestaShopCodingStandard": "dev-master"
                }
        }

Since the package is managed with `Packagist <https://packagist.org>`_ this is all what you need.

These commands will install the PHP_CodeSniffer into the *vendor/* folder of your project. For more informations about Composer have a look at their `documentation <http://getcomposer.org/doc/00-intro.md>`_.

PEAR
----

1. Install PHP_CodeSniffer:
::

        $ pear install PHP_CodeSniffer

2. Find your PEAR directory:
::

        $ pear config-show | grep php_dir

3. Copy, symlink or check out this repo to a folder called Prestashop inside the phpcs `Standards` directory:
::

        $ cd /path/to/pear/PHP/CodeSniffer/Standards
        $ git clone https://github.com/PrestaShop/PrestaShop-norm-validator.git Prestashop


Does it sniff?
--------------


Check if the standard installed correctly by call the following from the terminal:

::

        $ phpcs -i
        $ The installed coding standards are MySource, PEAR, PHPCS, Prestashop, PSR1, PSR2, Squiz, and Zend
