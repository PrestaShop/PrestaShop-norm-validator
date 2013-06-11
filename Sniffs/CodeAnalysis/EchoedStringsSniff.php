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

/**
 * Squiz_Sniffs_Strings_EchoedStringsSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: EchoedStringsSniff.php 8456 2011-09-09 15:56:52Z rMalie $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Squiz_Sniffs_Strings_EchoedStringsSniff.
 *
 * Makes sure that any strings that are "echoed" are not enclosed in brackets
 * like a function call.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Prestashop_Sniffs_CodeAnalysis_EchoedStringsSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_ECHO);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $firstContent = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 1), null, true);
        // If the first non-whitespace token is not an opening parenthesis, then we are not concerned.
        if ($tokens[$firstContent]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        $endOfStatement = $phpcsFile->findNext(array(T_SEMICOLON), $stackPtr, null, false);

        // If the token before the semi-colon is not a closing parenthesis, then we are not concerned.
        if ($tokens[($endOfStatement - 1)]['code'] !== T_CLOSE_PARENTHESIS) {
            return;
        }

        if (($phpcsFile->findNext(PHP_CodeSniffer_Tokens::$operators, $stackPtr, $endOfStatement, false)) === false) {
            // There are no arithmetic operators in this.
            $error = 'Echoed strings should not be bracketed';
            $phpcsFile->addError($error, $stackPtr, 'HasBracket');
        }

    }//end process()


}//end class

?>
