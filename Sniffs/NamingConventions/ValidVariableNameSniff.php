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
 * Check variables names
 *
 * 	valid : $is_ok, $my_test, $i_love_prestashop
 * 	not valid :	$isOK, $mY_test, $IlOvEpReStAsHoP
 *
 */
class Prestashop_Sniffs_NamingConventions_ValidVariableNameSniff implements PHP_CodeSniffer_Sniff
{
	public $exceptions = array(
		'fieldsRequired',
		'fieldsSize',
		'fieldsValidate',
		'fieldsRequiredLang',
		'fieldsSizeLang',
		'fieldsValidateLang',
		'webserviceParameters',
		'langMultiShop',
		'currentIndex',
		'tabAccess',
		'displayName',
		'confirmUninstall',
		'className',
	);

    public function register()
    {
        return array(T_VARIABLE);
    }

    protected function makeRealName($varname)
    {
		$real_name = $varname;
		$real_name = preg_replace_callback('#([A-Z])([A-Z]*)#', create_function('$m', 'return \'_\'.strtolower($m[1].$m[2]);'), $real_name);
		$real_name = preg_replace('#_{2,}#', '_', $real_name);
		$real_name = preg_replace('#^_+#', '', $real_name);
		return $real_name;
    }

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
        $varname = ltrim($tokens[$stackPtr]['content'], '$');

        // Ignore PHP vars
        $keywords = array('_SERVER', '_GET', '_POST', '_REQUEST', '_SESSION', '_ENV', '_COOKIE', '_FILES', '_MODULE', 'GLOBALS');
        if (in_array($varname, $keywords))
        	return;

        // Check if variable name is valid
        if (!in_array($varname, $this->exceptions) && !preg_match('#^[a-z][a-z0-9]*(_[a-z0-9]+)*$#', $varname))
        {
            $error = 'Variable "%s" have not right syntax. Should be : "%s"';
            $phpcsFile->addWarning($error, $stackPtr, 'VariableNameNotValid', array(
            	'$'.$varname,
            	'$'.$this->makeRealName($varname),
            ));
        }

        // Now check if there is an object member after the variable
        $next = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 1), null, true);
        if ($tokens[$next]['code'] === T_OBJECT_OPERATOR)
        {
			$nextMember = $phpcsFile->findNext(array(T_WHITESPACE), ($next + 1), null, true);
			if ($tokens[$nextMember]['code'] === T_STRING)
			{
				// Check if this is not a function
				$nextBracket = $objOperator = $phpcsFile->findNext(array(T_WHITESPACE), ($nextMember + 1), null, true);
                if ($tokens[$nextBracket]['code'] !== T_OPEN_PARENTHESIS)
                {
                	$membername = $tokens[$nextMember]['content'];
                	if (!in_array($membername, $this->exceptions) && !preg_match('#^[_a-z][a-z0-9]*(_[a-z0-9]+)*$#', $membername))
                	{
			            $error = 'Variable "%s" have not right syntax. Should be: "%s"';
			            $phpcsFile->addWarning($error, $stackPtr, 'VariableNameNotValid', array(
			            	'->'.$membername,
			            	'->'.$this->makeRealName($membername),
			            ));
                	}
                }
			}
        }
    }
}
