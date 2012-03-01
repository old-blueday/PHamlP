<?php
/* SVN FILE: $Id$ */
/**
 * SassScript Parser exception class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */

/*
require_once(dirname(__FILE__).'/../SassException.php');
*/

/**
 * Sass_script_SassScriptParserException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Sass_script_SassScriptParserException extends Sass_SassException {}

/**
 * Sass_script_SassScriptLexerException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Sass_script_SassScriptLexerException extends Sass_script_SassScriptParserException {}

/**
 * Sass_script_SassScriptOperationException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Sass_script_SassScriptOperationException extends Sass_script_SassScriptParserException {}

/**
 * Sass_script_SassScriptFunctionException class.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Sass_script_SassScriptFunctionException extends Sass_script_SassScriptParserException {}