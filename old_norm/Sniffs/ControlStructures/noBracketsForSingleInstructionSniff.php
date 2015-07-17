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
 * Warn about brackets for single intruction line
 * 
 * 	if ($foo)
 * 	{
 * 		something();
 * 	}
 * will trigger a warning.
 * 
 * 	if ($foo)
 * 	{
 * 		while ($bar)
 * 			something();
 * 	}
 * won't trigger any warning.
 * 
 */
class Prestashop_Sniffs_ControlStructures_noBracketsForSingleInstructionSniff implements PHP_CodeSniffer_Sniff
{
	protected static $instructionsKeywords = array(
                T_IF,
                T_WHILE,
                T_FOREACH,
                T_FOR,
                T_SWITCH,
                T_DO,
                T_ELSE,
                T_ELSEIF,
               );

    public function register()
    {
        return self::$instructionsKeywords;

    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
		$tokens = $phpcsFile->getTokens();
		
		// We don't need to check instructions without brackets
		if (!isset($tokens[$stackPtr]['scope_opener']))
			return ;

		$diffLines = $tokens[$tokens[$stackPtr]['scope_closer']]['line'] - $tokens[$tokens[$stackPtr]['scope_opener']]['line'] - 1;
		if ($diffLines == 1)
		{
            $error = 'Do not use brackets { } for single line in "%s"';
            $phpcsFile->addError($error, $stackPtr, 'BracketsForSingleInstruction', array(
            	$tokens[$stackPtr]['content'].' ()',
            ));
		}
    }
}
