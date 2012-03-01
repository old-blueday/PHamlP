<?php
/* SVN FILE: $Id$ */
/**
 * Sass literal exception classes.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */

require_once(dirname(__FILE__).'/../SassScriptParserExceptions.php');

/**
 * Sass literal exception.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Sass_script_literals_SassLiteralException extends Sass_script_SassScriptParserException {}

/**
 * Sass_script_literals_SassBooleanException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Sass_script_literals_SassBooleanException extends Sass_script_literals_SassLiteralException {}

/**
 * Sass_script_literals_SassColourException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Sass_script_literals_SassColourException extends Sass_script_literals_SassLiteralException {}

/**
 * Sass_script_literals_SassNumberException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Sass_script_literals_SassNumberException extends Sass_script_literals_SassLiteralException {}

/**
 * Sass_script_literals_SassStringException class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Sass_script_literals_SassStringException extends Sass_script_literals_SassLiteralException {}