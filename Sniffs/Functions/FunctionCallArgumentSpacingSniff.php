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
 * Generic_Sniffs_Functions_FunctionCallArgumentSpacingSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: FunctionCallArgumentSpacingSniff.php 8456 2011-09-09 15:56:52Z rMalie $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Generic_Sniffs_Functions_FunctionCallArgumentSpacingSniff.
 *
 * Checks that calls to methods and functions are spaced correctly.
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
class Prestashop_Sniffs_Functions_FunctionCallArgumentSpacingSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING);

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

        // Skip tokens that are the names of functions or classes
        // within their definitions. For example:
        // function myFunction...
        // "myFunction" is T_STRING but we should skip because it is not a
        // function or method *call*.
        $functionName    = $stackPtr;
        $ignoreTokens    = PHP_CodeSniffer_Tokens::$emptyTokens;
        $ignoreTokens[]  = T_BITWISE_AND;
        $functionKeyword = $phpcsFile->findPrevious($ignoreTokens, ($stackPtr - 1), null, true);
        if ($tokens[$functionKeyword]['code'] === T_FUNCTION || $tokens[$functionKeyword]['code'] === T_CLASS) {
            return;
        }

        // If the next non-whitespace token after the function or method call
        // is not an opening parenthesis then it cant really be a *call*.
        $openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($functionName + 1), null, true);
        if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        $closeBracket = $tokens[$openBracket]['parenthesis_closer'];

        $nextSeperator = $openBracket;
        while (($nextSeperator = $phpcsFile->findNext(array(T_WHITESPACE, T_COMMA, T_VARIABLE), ($nextSeperator + 1), $closeBracket)) !== false) {
            // Make sure the comma or variable belongs directly to this function call,
            // and is not inside a nested function call or array.
            $brackets    = $tokens[$nextSeperator]['nested_parenthesis'];
            $lastBracket = array_pop($brackets);
            if ($lastBracket !== $closeBracket) {
                continue;
            }

            if ($tokens[($nextSeperator - 1)]['code'] === T_OPEN_PARENTHESIS) {
                if (($tokens[($nextSeperator)]['code']) === T_WHITESPACE) {
                    $error = 'Space found after opening parenthesis in function call';
                    $phpcsFile->addError($error, $stackPtr, 'SpaceAfterOpeningParenthesis');
                }
            }

             if ($tokens[($nextSeperator + 1)]['code'] === T_CLOSE_PARENTHESIS) {
                if (($tokens[($nextSeperator)]['code']) === T_WHITESPACE) {
                    $error = 'Space found before closing parenthesis in function call';
                    $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeClosingParenthesis');
                }
            }

            if ($tokens[$nextSeperator]['code'] === T_COMMA) {
                if ($tokens[($nextSeperator - 1)]['code'] === T_WHITESPACE) {
                    $error = 'Space found before comma in function call';
                    $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeComma');
                }

                if ($tokens[($nextSeperator + 1)]['code'] !== T_WHITESPACE) {
                    $error = 'No space found after comma in function call';
                    $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterComma');
                } else {
                    // If there is a newline in the space, then the must be formatting
                    // each argument on a newline, which is valid, so ignore it.
                    if (strpos($tokens[($nextSeperator + 1)]['content'], $phpcsFile->eolChar) === false) {
                        $space = strlen($tokens[($nextSeperator + 1)]['content']);
                        if ($space > 1) {
                            $error = 'Expected 1 space after comma in function call; %s found';
                            $data  = array($space);
                            $phpcsFile->addError($error, $stackPtr, 'TooMuchSpaceAfterComma', $data);
                        }
                    }
                }
            } else {
                // Token is a variable.
                $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextSeperator + 1), $closeBracket, true);
                if ($nextToken !== false) {
                    if ($tokens[$nextToken]['code'] === T_EQUAL) {
                        if (($tokens[($nextToken - 1)]['code']) !== T_WHITESPACE) {
                            $error = 'Expected 1 space before = sign of default value';
                            $phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeEquals');
                        }

                        if ($tokens[($nextToken + 1)]['code'] !== T_WHITESPACE) {
                            $error = 'Expected 1 space after = sign of default value';
                            $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterEquals');
                        }
                    }
                }
            }//end if
        }//end while

    }//end process()


}//end class

?>
