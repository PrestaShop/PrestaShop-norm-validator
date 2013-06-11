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
 * Display a warning for todo
 *
 */
class Prestashop_Sniffs_Commenting_CommentsInFunctionSniff extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{
    public function __construct()
    {
        parent::__construct(array(T_FUNCTION), array(T_COMMENT, T_DOC_COMMENT), true);

    }

    protected function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $tokens = $phpcsFile->getTokens();

        // Issues with this sniff, let's desactivate temporary comments checks in fucntions
        return;

        // Comments with // are allowed inside functions
        if ($tokens[$stackPtr]['code'] == T_COMMENT)
        	return ;

		$error = 'Bad comment type in function: %s';
		$phpcsFile->addError($error, $stackPtr, 'BadCommentType', array(
			trim($tokens[$stackPtr]['content']),
		));
    }

    protected function processTokenOutsideScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
    	$tokens = $phpcsFile->getTokens();

    	// Comments with /* */ are allowed outside functions
    	if (!preg_match('#^//#', $tokens[$stackPtr]['content']))
        	return ;

        $error = 'Bad comment type outside of function: %s';
		$phpcsFile->addError($error, $stackPtr, 'BadCommentTypeOutside', array(
			trim($tokens[$stackPtr]['content']),
		));
    }
}
