<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found');
}

/**
 * Check if methods in classes have all a scope (public, protected, private)
 * 
 */
class Prestashop_Sniffs_Functions_FunctionNeedScopeSniff extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{

    public function __construct()
    {
        parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION));

    }

    protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $tokens = $phpcsFile->getTokens();

    	$previous = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
		if (!in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$scopeModifiers))
		{
			// If static keyword found, check the keyword before
			if ($tokens[$previous]['code'] === T_STATIC)
			{
				$previous = $phpcsFile->findPrevious(T_WHITESPACE, $previous - 1, null, true);
				if (in_array($tokens[$previous]['code'], PHP_CodeSniffer_Tokens::$scopeModifiers))
					return ;
			}

            $error = 'function "%s()" need explicit "public" scope';
            $phpcsFile->addError($error, $stackPtr, 'FunctionNeedScope', array(
            	$phpcsFile->getDeclarationName($stackPtr),
            ));
		}
    }
}
