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
 * Check spaces in function declaration are checked where * is placed :
 * 	function*foo*(*$bar*,*$bar2*)
 * White spaces verification is ignored is argument is on a new line.
 * 
 */
class Prestashop_Sniffs_WhiteSpace_FunctionDeclarationSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(T_FUNCTION);

    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check if there is only 1 space after 'function' keyword
        if ($tokens[$stackPtr + 1]['code'] === T_WHITESPACE && strlen($tokens[$stackPtr + 1]['content']) > 1)
        {
       		$error = '1 space expected after "function" keyword; %s found';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
            	strlen($tokens[$stackPtr + 1]['content'])
            ));
        }
        
		// Check if there is no space between function name and open parenthesis
		$next = $phpcsFile->findNext(T_STRING, $stackPtr);
		if ($tokens[$next + 1]['code'] === T_WHITESPACE)
		{
       		$error = '0 space expected between function name "%s" and parenthesis; %s found';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
            	$phpcsFile->getDeclarationName($stackPtr),
            	strlen($tokens[$next + 1]['content']),
            ));
		}

		$next = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $next);
		
		// No space expected after first parenthesis
		if ($tokens[$next + 1]['code'] === T_WHITESPACE)
		{
       		$error = '0 space expected after first parenthesis of "%s"; %s found';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
            	$phpcsFile->getDeclarationName($stackPtr),
            	strlen($tokens[$next + 1]['content']),
            ));
		}
		
		$ptr = $next + 1;
		$last = $tokens[$stackPtr]['parenthesis_closer'];
		while ($phpcsFile->findNext(T_VARIABLE, $ptr, $last) !== false)
		{
			// Check type hinting
			$ptr = $phpcsFile->findNext(T_WHITESPACE, $ptr, $last, true);
			if ($tokens[$ptr]['code'] === T_ARRAY_HINT || $tokens[$ptr]['code'] === T_STRING)
			{
				if ($tokens[$ptr + 1]['code'] === T_WHITESPACE && strlen($tokens[$ptr + 1]['content']) > 1)
				{
		       		$error = '1 space expected after type hinting "%s"; %s found';
		            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterStructure', array(
		            	$tokens[$ptr]['content'],
		            	strlen($tokens[$ptr + 1]['content']),
		            ));
				}
			}
			
			// Now check if we have a default argument
			$ptr = $phpcsFile->findNext(T_VARIABLE, $ptr, $last);
			if ($tokens[$phpcsFile->findNext(T_WHITESPACE, $ptr + 1, $last, true)]['code'] === T_EQUAL)
			{
				// Only 1 space between variable and assignement
				if ($tokens[$ptr + 1]['code'] !== T_WHITESPACE)
				{
		       		$error = '1 space expected before variable assignement "="; 0 found';
		            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeVarFunctionAssignement');
				}
				else if (strlen($tokens[$ptr + 1]['content']) > 1)
				{
					$error = '1 space expected before variable assignement "="; %s found';
		            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeVarFunctionAssignement', array(
		            	strlen($tokens[$ptr + 1]['content']),
		            ));
				}
				
				// Only 1 space between assignement and value
				$ptr = $phpcsFile->findNext(T_WHITESPACE, $ptr + 1, $last, true);
				if ($tokens[$ptr + 1]['code'] !== T_WHITESPACE)
				{
		       		$error = '1 space expected after variable assignement "="; 0 found';
		            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterVarFunctionAssignement');
				}
				else if (strlen($tokens[$ptr + 1]['content']) > 1)
				{
					$error = '1 space expected before variable assignement "="; %s found';
		            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterVarFunctionAssignement', array(
		            	strlen($tokens[$ptr + 1]['content']),
		            ));
				}
			}
			
			// Find next comma, if no comma found there are no more arguments
			$ptr = $phpcsFile->findNext(T_COMMA, $ptr + 1, $last);
			if ($ptr === false)
				break;

			// Check spaces before comma
			if ($tokens[$ptr - 1]['code'] === T_WHITESPACE)
			{
				$error = '0 space expected before comma ","; %s found';
	            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeComma', array(
	            	strlen($tokens[$ptr - 1]['content']),
	            ));
			}
			
			// Check spaces after comma
			if ($tokens[$ptr + 1]['code'] !== T_WHITESPACE)
			{
				$error = '1 space expected after comma ","; 0 found';
	            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeComma');
			}
			else if (strlen($tokens[$ptr + 1]['content']) > 1 && $tokens[$ptr]['line'] == $tokens[$phpcsFile->findNext(T_WHITESPACE, $ptr + 1, $last, true)]['line'])
			{
				$error = '1 space expected after comma ","; %s found';
	            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeComma', array(
	            	strlen($tokens[$ptr + 1]['content']),
	            ));
			}
			$ptr++;
		}
    }
}
