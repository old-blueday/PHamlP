<?php
/* SVN FILE: $Id$ */
/**
 * Sass_script_literals_SassLiteral class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */

/*
require_once('SassLiteralExceptions.php');
*/

/**
 * Sass_script_literals_SassLiteral class.
 * Base class for all Sass literals.
 * Sass data types are extended from this class and these override the operation
 * methods to provide the appropriate semantics.
 * @package			PHamlP
 * @subpackage	Sass.script.literals
 */
abstract class Sass_script_literals_SassLiteral {
	/**
	 * @var array maps class names to data types
	 */
	static private $typeOf = array(
		'Sass_script_literals_SassBoolean' => 'bool',
		'Sass_script_literals_SassColour'  => 'color',
		'Sass_script_literals_SassNumber'  => 'number',
		'Sass_script_literals_SassString'  => 'string'
	);

	/**
	 * @var mixed value of the literal type
	 */
  protected $value;

	/**
	 * class constructor
	 * @param string value of the literal type
	 * @return Sass_script_literals_SassLiteral
	 */
	public function __construct($value = null, $context) {
		$this->value = $value;
		$this->context = $context;
	}

	/**
	 * Getter.
	 * @param string name of property to get
	 * @return mixed return value of getter function
	 */
	public function __get($name) {
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		else {
			throw new Sass_script_literals_SassLiteralException('No getter function for {what}', array('{what}'=>$name), array(), Sass_script_SassScriptParser::$context->node);
		}
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 * Returns the boolean representation of the value of this
	 * @return boolean the boolean representation of the value of this
	 */
	public function toBoolean() {
		return (boolean)$this->value;
	}

	/**
	 * Returns the type of this
	 * @return string the type of this
	 */
	protected function getTypeOf() {
		return self::$typeOf[get_class($this)];
	}

	/**
	 * Returns the value of this
	 * @return mixed the value of this
	 */
	protected function getValue() {
		throw new Sass_script_literals_SassLiteralException('Child classes must override this method', array(), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Adds a child object to this.
	 * @param sassLiteral the child object
	 */
	public function addChild($sassLiteral) {
		$this->children[] = $sassLiteral;
	}

	/**
	 * SassScript '+' operation.
	 * @param sassLiteral value to add
	 * @return sassString the string values of this and other with no seperation
	 */
	public function op_plus($other) {
		return new Sass_script_literals_SassString($this->toString().$other->toString());
	}

	/**
	 * SassScript '-' operation.
	 * @param Sass_script_literals_SassLiteral value to subtract
	 * @return sassString the string values of this and other seperated by '-'
	 */
	public function op_minus($other) {
		return new Sass_script_literals_SassString($this->toString().'-'.$other->toString());
	}

	/**
	 * SassScript '*' operation.
	 * @param Sass_script_literals_SassLiteral value to multiply by
	 * @return sassString the string values of this and other seperated by '*'
	 */
	public function op_times($other) {
		return new Sass_script_literals_SassString($this->toString().'*'.$other->toString());
	}

	/**
	 * SassScript '/' operation.
	 * @param Sass_script_literals_SassLiteral value to divide by
	 * @return sassString the string values of this and other seperated by '/'
	 */
	public function op_div($other) {
		return new Sass_script_literals_SassString($this->toString().'/'.$other->toString());
	}

	/**
	 * SassScript '%' operation.
	 * @param Sass_script_literals_SassLiteral value to take the modulus of
	 * @return Sass_script_literals_SassLiteral result
	 * @throws Exception if modulo not supported for the data type
	 */
	public function op_modulo($other) {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Modulus')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Bitwise AND the value of other and this value
	 * @param string value to bitwise AND with
	 * @return string result
	 * @throws Exception if bitwise AND not supported for the data type
	 */
	public function op_bw_and($other) {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise AND')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Bitwise OR the value of other and this value
	 * @param Sass_script_literals_SassNumber value to bitwise OR with
	 * @return string result
	 * @throws Exception if bitwise OR not supported for the data type
	 */
	public function op_bw_or($other) {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise OR')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Bitwise XOR the value of other and the value of this
	 * @param Sass_script_literals_SassNumber value to bitwise XOR with
	 * @return string result
	 * @throws Exception if bitwise XOR not supported for the data type
	 */
	public function op_bw_xor($other) {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise XOR')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Bitwise NOT the value of other and the value of this
	 * @param Sass_script_literals_SassNumber value to bitwise NOT with
	 * @return string result
	 * @throws Exception if bitwise NOT not supported for the data type
	 */
	public function op_bw_not() {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise NOT')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Shifts the value of this left by the number of bits given in value
	 * @param Sass_script_literals_SassNumber amount to shift left by
	 * @return string result
	 * @throws Exception if bitwise Shift Left not supported for the data type
	 */
	public function op_shiftl($other) {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise Shift Left')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * Shifts the value of this right by the number of bits given in value
	 * @param Sass_script_literals_SassNumber amount to shift right by
	 * @return string result
	 * @throws Exception if bitwise Shift Right not supported for the data type
	 */
	public function op_shiftr($other) {
		throw new Sass_script_literals_SassLiteralException('{class} does not support {operation}.', array('{class}'=>get_class($this), '{operation}'=>Phamlp::t('sass', 'Bitwise Shift Right')), Sass_script_SassScriptParser::$context->node);
	}

	/**
	 * The SassScript and operation.
	 * @param sassLiteral the value to and with this
	 * @return Sass_script_literals_SassLiteral other if this is boolean true, this if false
	 */
	public function op_and($other) {
		return ($this->toBoolean() ? $other : $this);
	}

	/**
	 * The SassScript or operation.
	 * @param sassLiteral the value to or with this
	 * @return Sass_script_literals_SassLiteral this if this is boolean true, other if false
	 */
	public function op_or($other) {
		return ($this->toBoolean() ? $this : $other);
	}

	/**
	 * The SassScript xor operation.
	 * @param sassLiteral the value to xor with this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if this or
	 * other, but not both, are true, false if not
	 */
	public function op_xor($other) {
		return new Sass_script_literals_SassBoolean($this->toBoolean() xor $other->toBoolean());
	}

	/**
	 * The SassScript not operation.
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if the
	 * boolean of this is false or false if it is true
	 */
	public function op_not() {
		return new Sass_script_literals_SassBoolean(!$this->toBoolean());
	}

	/**
	 * The SassScript > operation.
	 * @param sassLiteral the value to compare to this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if the values
	 * of this is greater than the value of other, false if it is not
	 */
	public function op_gt($other) {
		return new Sass_script_literals_SassBoolean($this->value > $other->value);
	}

	/**
	 * The SassScript >= operation.
	 * @param sassLiteral the value to compare to this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if the values
	 * of this is greater than or equal to the value of other, false if it is not
	 */
	public function op_gte($other) {
		return new Sass_script_literals_SassBoolean($this->value >= $other->value);
	}

	/**
	 * The SassScript < operation.
	 * @param sassLiteral the value to compare to this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if the values
	 * of this is less than the value of other, false if it is not
	 */
	public function op_lt($other) {
		return new Sass_script_literals_SassBoolean($this->value < $other->value);
	}

	/**
	 * The SassScript <= operation.
	 * @param sassLiteral the value to compare to this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if the values
	 * of this is less than or equal to the value of other, false if it is not
	 */
	public function op_lte($other) {
		return new Sass_script_literals_SassBoolean($this->value <= $other->value);
	}

	/**
	 * The SassScript == operation.
	 * @param sassLiteral the value to compare to this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if this and
	 * other are equal, false if they are not
	 */
	public function op_eq($other) {
		return new Sass_script_literals_SassBoolean($this == $other);
	}

	/**
	 * The SassScript != operation.
	 * @param sassLiteral the value to compare to this
	 * @return Sass_script_literals_SassBoolean Sass_script_literals_SassBoolean object with the value true if this and
	 * other are not equal, false if they are
	 */
	public function op_neq($other) {
		return new Sass_script_literals_SassBoolean(!$this->op_eq($other)->toBoolean());
	}

	/**
	 * The SassScript default operation (e.g. $a $b, "foo" "bar").
	 * @param sassLiteral the value to concatenate with a space to this
	 * @return sassString the string values of this and other seperated by " "
	 */
	public function op_concat($other) {
		return new Sass_script_literals_SassString($this->toString().' '.$other->toString());
	}

	/**
	 * SassScript ',' operation.
	 * @param sassLiteral the value to concatenate with a comma to this
	 * @return sassString the string values of this and other seperated by ","
	 */
	public function op_comma($other) {
		return new Sass_script_literals_SassString($this->toString().', '.$other->toString());
	}

	/**
	 * Asserts that the literal is the expected type
	 * @param Sass_script_literals_SassLiteral the literal to test
	 * @param string expected type
	 * @throws Sass_script_SassScriptFunctionException if value is not the expected type
	 */
	public static function assertType($literal, $type) {
		if (!$literal instanceof $type) {
			throw new Sass_script_SassScriptFunctionException('{what} must be a {type}', array('{what}'=>($literal instanceof Sass_script_literals_SassLiteral ? $literal->typeOf : 'literal'), '{type}'=>$type), Sass_script_SassScriptParser::$context->node);
		}
	}

	/**
	 * Asserts that the value of a literal is within the expected range
	 * @param Sass_script_literals_SassLiteral the literal to test
	 * @param float the minimum value
	 * @param float the maximum value
	 * @param string the units.
	 * @throws Sass_script_SassScriptFunctionException if value is not the expected type
	 */
	 public static function assertInRange($literal, $min, $max, $units = '') {
	 	 if ($literal->value < $min || $literal->value > $max) {
			throw new Sass_script_SassScriptFunctionException('{what} must be {inRange}', array('{what}'=>$literal->typeOf, '{inRange}'=>Phamlp::t('sass', 'between {min} and {max} inclusive', array('{min}'=>$min.$units, '{max}'=>$max.$units))), Sass_script_SassScriptParser::$context->node);
		}
	}

	/**
	 * Returns a string representation of the value.
	 * @return string string representation of the value.
	 */
	abstract public function toString();

	/**
	 * Returns a value indicating if a token of this type can be matched at
	 * the start of the subject string.
	 * @param string the subject string
	 * @return mixed match at the start of the string or false if no match
	 */
	abstract public static function isa($subject);
}
