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
 * Check spaces in control structures (places with * are checked) : if*(*condition*) 
 *
 */
class Prestashop_Sniffs_WhiteSpace_ControlStructureSpacingSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(
                T_IF,
                T_WHILE,
                T_FOREACH,
                T_FOR,
                T_SWITCH,
                T_ELSEIF,
               );

    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

		// Check spaces before first parenthesis
        if ($tokens[$stackPtr + 1]['code'] === T_WHITESPACE)
        	$spaces = strlen($tokens[$stackPtr + 1]['content']);
        else
        	$spaces = 0;
        
        if ($spaces != 1)
        {
       		$error = '1 space expected after "%s"; %s found';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
            	$tokens[$stackPtr]['content'],
            	$spaces,
            ));
        }

        // Check spaces after first parenthesis
       	$spaces = 0;
       	if ($tokens[$tokens[$stackPtr]['parenthesis_opener'] + 1]['code'] === T_WHITESPACE)
       		$spaces = strlen($tokens[$tokens[$stackPtr]['parenthesis_opener'] + 1]['content']);
       		
        if ($spaces > 0)
        {
       		$error = '1 space expected after parenthesis "%s"; %s found';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
            	$tokens[$stackPtr]['content'].' ( <-',
            	$spaces,
            ));
        }
       		
       	// Check spaces before last parenthesis
       	$spaces = 0;
       	if ($tokens[$tokens[$stackPtr]['parenthesis_closer'] - 1]['code'] === T_WHITESPACE)
       		$spaces = strlen($tokens[$tokens[$stackPtr]['parenthesis_closer'] - 1]['content']);
       		
    	if ($spaces > 0)
        {
       		$error = 'No space expected before last parenthesis "%s"; %s found';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
            	$tokens[$stackPtr]['content'].' (... -> )',
            	$spaces,
            ));
        }
    }
}
