<?php
/* SVN FILE: $Id$ */
/**
 * Sass_script_literals_SassBoolean class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */

/*
require_once('SassLiteral.php');
*/

/**
 * Sass_script_literals_SassBoolean class.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
class Sass_script_literals_SassBoolean extends Sass_script_literals_SassLiteral {
	/**@#+
	 * Regex for matching and extracting booleans
	 */
	const MATCH = '/^(true|false)\b/';

	/**
	 * Sass_script_literals_SassBoolean constructor
	 * @param string value of the boolean type
	 * @return Sass_script_literals_SassBoolean
	 */
	public function __construct($value) {
		if (is_bool($value)) {
			$this->value = $value;
		}
		elseif ($value === 'true' || $value === 'false') {
			$this->value = ($value === 'true' ? true : false);
		}
		else {
			throw new Sass_script_literals_SassBooleanException('Invalid {what}', array('{what}'=>'Sass_script_literals_SassBoolean'), Sass_script_SassScriptParser::$context->node);
		}
	}

	/**
	 * Returns the value of this boolean.
	 * @return boolean the value of this boolean
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Returns a string representation of the value.
	 * @return string string representation of the value.
	 */
	public function toString() {
		return $this->getValue() ? 'true' : 'false';
	}

	/**
	 * Returns a value indicating if a token of this type can be matched at
	 * the start of the subject string.
	 * @param string the subject string
	 * @return mixed match at the start of the string or false if no match
	 */
	public static function isa($subject) {
		return (preg_match(self::MATCH, $subject, $matches) ? $matches[0] : false);
	}
}
