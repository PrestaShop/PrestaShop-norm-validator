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
 * Squiz_Sniffs_ControlStructures_ForEachLoopDeclarationSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: ForEachLoopDeclarationSniff.php 8456 2011-09-09 15:56:52Z rMalie $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Squiz_Sniffs_ControlStructures_ForEachLoopDeclarationSniff.
 *
 * Verifies that there is a space between each condition of foreach loops.
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
class Prestashop_Sniffs_ControlStructures_ForEachLoopDeclarationSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FOREACH);

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

        $openingBracket = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);
        $closingBracket = $tokens[$openingBracket]['parenthesis_closer'];

        if ($tokens[($openingBracket + 1)]['code'] === T_WHITESPACE) {
            $error = 'Space found after opening bracket of FOREACH loop';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterOpen');
        }

        if ($tokens[($closingBracket - 1)]['code'] === T_WHITESPACE) {
            $error = 'Space found before closing bracket of FOREACH loop';
            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeClose');
        }

        $asToken = $phpcsFile->findNext(T_AS, $openingBracket);
        $content = $tokens[$asToken]['content'];
        if ($content !== strtolower($content)) {
            $expected = strtolower($content);
            $error    = 'AS keyword must be lowercase; expected "%s" but found "%s"';
            $data     = array(
                         $expected,
                         $content,
                        );
            $phpcsFile->addError($error, $stackPtr, 'AsNotLower', $data);
        }

        $doubleArrow = $phpcsFile->findNext(T_DOUBLE_ARROW, $openingBracket, $closingBracket);

        if ($doubleArrow !== false) {
            if ($tokens[($doubleArrow - 1)]['code'] !== T_WHITESPACE) {
                $error = 'Expected 1 space before "=>"; 0 found';
                $phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeArrow');
            } else {
                if (strlen($tokens[($doubleArrow - 1)]['content']) !== 1) {
                    $spaces = strlen($tokens[($doubleArrow - 1)]['content']);
                    $error  = 'Expected 1 space before "=>"; %s found';
                    $data   = array($spaces);
                    $phpcsFile->addError($error, $stackPtr, 'SpacingBeforeArrow', $data);
                }

            }

            if ($tokens[($doubleArrow + 1)]['code'] !== T_WHITESPACE) {
                $error = 'Expected 1 space after "=>"; 0 found';
                $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterArrow');
            } else {
                if (strlen($tokens[($doubleArrow + 1)]['content']) !== 1) {
                    $spaces = strlen($tokens[($doubleArrow + 1)]['content']);
                    $error  = 'Expected 1 space after "=>"; %s found';
                    $data   = array($spaces);
                    $phpcsFile->addError($error, $stackPtr, 'SpacingAfterArrow', $data);
                }

            }

        }//end if

        if ($tokens[($asToken - 1)]['code'] !== T_WHITESPACE) {
            $error = 'Expected 1 space before "as"; 0 found';
            $phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeAs');
        } else {
            if (strlen($tokens[($asToken - 1)]['content']) !== 1) {
                $spaces = strlen($tokens[($asToken - 1)]['content']);
                $error  = 'Expected 1 space before "as"; %s found';
                $data   = array($spaces);
                $phpcsFile->addError($error, $stackPtr, 'SpacingBeforeAs', $data);
            }
        }

        if ($tokens[($asToken + 1)]['code'] !== T_WHITESPACE) {
            $error = 'Expected 1 space after "as"; 0 found';
            $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterAs');
        } else {
            if (strlen($tokens[($asToken + 1)]['content']) !== 1) {
                $spaces = strlen($tokens[($asToken + 1)]['content']);
                $error  = 'Expected 1 space after "as"; %s found';
                $data   = array($spaces);
                $phpcsFile->addError($error, $stackPtr, 'SpacingAfterAs', $data);
            }
        }

    }//end process()


}//end class

?>
