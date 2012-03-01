<?php
/* SVN FILE: $Id$ */
/**
 * Sass_script_SassScriptLexer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script
 */

require_once('literals/SassBoolean.php');
require_once('literals/SassColour.php');
require_once('literals/SassNumber.php');
require_once('literals/SassString.php');
require_once('SassScriptFunction.php');
require_once('SassScriptOperation.php');
require_once('SassScriptVariable.php');

/**
 * Sass_script_SassScriptLexer class.
 * Lexes SassSCript into tokens for the parser.
 * 
 * Implements a {@link http://en.wikipedia.org/wiki/Shunting-yard_algorithm Shunting-yard algorithm} to provide {@link http://en.wikipedia.org/wiki/Reverse_Polish_notation Reverse Polish notation} output.
 * @package			PHamlP
 * @subpackage	Sass.script
 */
class Sass_script_SassScriptLexer {
	const MATCH_WHITESPACE = '/^\s+/';

	/**
	 * @var Sass_script_SassScriptParser the parser object
	 */
	protected $parser;

	/**
	* Sass_script_SassScriptLexer constructor.
	* @return Sass_script_SassScriptLexer
	*/
	public function __construct($parser) {
		$this->parser = $parser;
	}
	
	/**
	 * Lex an expression into SassScript tokens.
	 * @param string expression to lex
	 * @param Sass_tree_SassContext the context in which the expression is lexed
	 * @return array tokens
	 */
	public function lex($string, $context) {
		$tokens = array();
		while ($string !== false) {
			if (($match = $this->isWhitespace($string)) !== false) {
				$tokens[] = null;
			}
			elseif (($match = Sass_script_SassScriptFunction::isa($string)) !== false) {
				preg_match(Sass_script_SassScriptFunction::MATCH_FUNC, $match, $matches);
				
				$args = array();
				foreach (Sass_script_SassScriptFunction::extractArgs($matches[Sass_script_SassScriptFunction::ARGS])
						as $expression) {
					$args[] = $this->parser->evaluate($expression, $context);
				}
				
				$tokens[] = new Sass_script_SassScriptFunction(
						$matches[Sass_script_SassScriptFunction::NAME], $args);
			}
			elseif (($match = Sass_script_literals_SassString::isa($string)) !== false) {
				$tokens[] = new Sass_script_literals_SassString($match);
			}
			elseif (($match = Sass_script_literals_SassBoolean::isa($string)) !== false) {
				$tokens[] = new Sass_script_literals_SassBoolean($match);
			}
			elseif (($match = Sass_script_literals_SassColour::isa($string)) !== false) {
				$tokens[] = new Sass_script_literals_SassColour($match);
			}
			elseif (($match = Sass_script_literals_SassNumber::isa($string)) !== false) {				
				$tokens[] = new Sass_script_literals_SassNumber($match);
			}
			elseif (($match = Sass_script_SassScriptOperation::isa($string)) !== false) {
				$tokens[] = new Sass_script_SassScriptOperation($match);
			}
			elseif (($match = Sass_script_SassScriptVariable::isa($string)) !== false) {
				$tokens[] = new Sass_script_SassScriptVariable($match);
			}
			else {
				$_string = $string;
				$match = '';
				while (strlen($_string) && !$this->isWhitespace($_string)) {
					foreach (Sass_script_SassScriptOperation::$inStrOperators as $operator) {
						if (substr($_string, 0, strlen($operator)) == $operator) {
							break 2;
						}
					}
					$match .= $_string[0];
					$_string = substr($_string, 1);			
				}
				$tokens[] = new Sass_script_literals_SassString($match);
			}			
			$string = substr($string, strlen($match));
		}
		return $tokens; 
	}

	/**
	 * Returns a value indicating if a token of this type can be matched at
	 * the start of the subject string.
	 * @param string the subject string
	 * @return mixed match at the start of the string or false if no match
	 */
	public function isWhitespace($subject) {
		return (preg_match(self::MATCH_WHITESPACE, $subject, $matches) ? $matches[0] : false);
	}
}
