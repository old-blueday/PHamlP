<?php
/* SVN FILE: $Id: SassBoolean.php 49 2010-04-04 10:51:24Z chris.l.yates $ */
/**
 * Compass extension SassScript colour stop objects and functions class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
 
/**
 * Compass extension List object.
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
class CompassList extends Sass_script_literals_SassLiteral {	
	public function __construct($values) {
		$this->value = $values;
	}
	
	public function getValues() {
		return $this->value;
	}

	/**
	 * Returns the type of this
	 * @return string the type of this
	 */
	protected function getTypeOf() {
		return 'list';
	}

	public function toString() {
		$values = array();
		foreach ($this->value as $value) {
			$values[] = $value->toString();
		}
		return join(', ', $values);
	}
	
	public static function isa($subject) {}
}

class CompassColourStop extends Sass_script_literals_SassLiteral {
	protected $colour;
	public $stop;
	  
	public function __construct($colour, $stop = null) {
		$this->colour = $colour;
		$this->stop = $stop;
	}
	
	protected function getColor() {
		return $this->getColour();
	}
	
	protected function getColour() {
		return $this->colour;
	}
	
	public function toString() {
		$s = $this->colour->toString();
		if (!empty($this->stop)) {
			$s .= ' ';
			if ($this->stop->isUnitless()) {
				$s .= $this->stop->op_times(new Sass_script_literals_SassNumber('100%'))->toString();
			}
			else {
				$s .= $this->stop->toString();
			}
		}
		return $s;
	}
	
	public static function isa($subject) {}
}
 
/**
 * Compass extension SassScript colour stops functions class.
 * A collection of functions for use in SassSCript.
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
class SassExtentionsCompassFunctionsColourStops {
	# returns color-stop() calls for use in webkit.
	public static function grad_color_stops($colour_list) {
		return self::grad_colour_stops($colour_list);
	}
	
	public static function grad_colour_stops($colour_list) {
		Sass_script_literals_SassLiteral::assertType($colour_list, 'CompassList');
		self::normalize_stops($colour_list);
		$v = array_reverse($colour_list->values);
		$max = $v[0]->stop;
		$last_value = null;
		
		$colourStops = array();
		
		foreach ($colour_list->values as $pos) {
			# have to convert absolute units to percentages for use in colour stop functions.
			$stop = $pos->stop;
			if ($stop->numeratorUnits === $max->numeratorUnits) {
				$stop = $stop->op_div($max)->op_times(new Sass_script_literals_SassNumber('100%'));
			}
			# Make sure the colour stops are specified in the right order.
			if ($last_value && $last_value->value > $stop->value) {
				throw new Sass_script_SassScriptFunctionException('Colour stops must be specified in increasing order', array(), Sass_script_SassScriptParser::$context->node);
			}
		 
			$last_value = $stop;
			$colourStops[] = "colour-stop({$stop->toString()}, {$pos->colour->toString()})";
		}
		
		return new Sass_script_literals_SassString(join(', ', $colourStops));
	}

	# returns the end position of the gradient from the colour stop
	public static function grad_end_position($colourList, $radial = null) {
		Sass_script_literals_SassLiteral::assertType($colourList, 'CompassList');
		if (is_null($radial)) {
			$radial = new Sass_script_literals_SassBoolean(false);
		}
		else {
			Sass_script_literals_SassLiteral::assertType($radial, 'Sass_script_literals_SassBoolean');
		}
		return self::grad_position($colourList, new Sass_script_literals_SassNumber(sizeof($colourList->values)), new Sass_script_literals_SassNumber(100), $radial);
	}

	public static function grad_position($colourList, $index, $default, $radial = null) {
		Sass_script_literals_SassLiteral::assertType($colourList, 'CompassList');
		if (is_null($radial)) {
			$radial = new Sass_script_literals_SassBoolean(false);
		}
		else {
			Sass_script_literals_SassLiteral::assertType($radial, 'Sass_script_literals_SassBoolean');
		}
		$stop = $colourList->values[$index->value - 1]->stop;
		if ($stop && $radial->value) {
			$orig_stop = $stop;
			if ($stop->isUnitless()) {
				if ($stop->value <= 1) {
					# A unitless number is assumed to be a percentage when it's between 0 and 1
					$stop = $stop->op_times(new Sass_script_literals_SassNumber('100%'));
				}
				else {
					# Otherwise, a unitless number is assumed to be in pixels
					$stop = $stop->op_times(new Sass_script_literals_SassNumber('1px'));
				}
			}
			
			if ($stop->numeratorUnits === '%' && isset($colourList->values[sizeof($colourList->values)-1]->stop) && $colourList->values[sizeof($colourList->values)-1]->stop->numeratorUnits === 'px')
				$stop = $stop->op_times($colourList->values[sizeof($colourList->values)-1]->stop)->op_div(new Sass_script_literals_SassNumber('100%'));
			//Compass::Logger.new.record(:warning, "Webkit only supports pixels for the start and end stops for radial gradients. Got: #{orig_stop}") if stop.numerator_units != ["px"];
			return $stop->op_div(new Sass_script_literals_SassNumber('1'.$stop->units));
		}
		elseif ($stop)
			return $stop;
		else
			return $default;
	}

	# takes the given position and returns a point in percentages
	public static function grad_point($position) {
		$position = $position->value;
		if (strpos($position, ' ') !== false) {
			if (preg_match('/(top|bottom|center) (left|right|center)/', $position, $matches)) 
				$position =  "{$matches[2]} {$matches[1]}";
		}
		else {
			switch ($position) {
				case 'top':
				case 'bottom':
					$position = "left $position";
					break;
				case 'left':
				case 'right':
					$position .= ' top';
					break;
			}
		}

		return new Sass_script_literals_SassString(preg_replace(
			array('/top/', '/bottom/', '/left/', '/right/', '/center/'),
			array('0%', '100%', '0%', '100%', '50%'), $position
		));
	}

	public static function color_stops() {
		return self::colour_stops(func_get_args());
	}
	
	public static function colour_stops() {
		$args = func_get_args();
		$list = array();
		
		foreach ($args as $arg) {
			if ($arg instanceof Sass_script_literals_SassColour) {
				$list[] = new CompassColourStop($arg);
			}
			elseif ($arg instanceof Sass_script_literals_SassString) {
				# We get a string as the result of concatenation
				# So we have to reparse the expression
				$colour = $stop = null;
				if (empty($parser))
					$parser = new Sass_script_SassScriptParser();
				$expr = $parser->parse($arg->value, Sass_script_SassScriptParser::$context);
				
				$x = array_pop($expr);
				
				if ($x instanceof Sass_script_literals_SassColour)
					$colour = $x;
				elseif ($x instanceof Sass_script_SassScriptOperation) {
					if ($x->operator != 'concat')
						# This should never happen.
						throw new Sass_script_SassScriptFunctionException("Couldn't parse a colour stop from: {value}", array('{value}'=>$arg->value), Sass_script_SassScriptParser::$context->node);
					$colour = $expr[0];
					$stop = $expr[1];
				}
				else
					throw new Sass_script_SassScriptFunctionException("Couldn't parse a colour stop from: {value}", array('{value}'=>$arg->value), Sass_script_SassScriptParser::$context->node);
				$list[] = new CompassColourStop($colour, $stop);
			}
			else
				throw new Sass_script_SassScriptFunctionException('Not a valid color stop: {arg}', array('{arg}'=>$arg->value), Sass_script_SassScriptParser::$context->node);
		}
		return new CompassList($list);
	}
	
	protected static function normalize_stops($colourList) {
		$positions = $colourList->values;
		$s = sizeof($positions);
		
		# fill in the start and end positions, if unspecified
		if (empty($positions[0]->stop))
			$positions[0]->stop = new Sass_script_literals_SassNumber(0);
		if (empty($positions[$s-1]->stop))
			$positions[$s-1]->stop = new Sass_script_literals_SassNumber('100%');

		# fill in empty values
		for ($i = 0; $i<$s; $i++) {
			if (is_null($positions[$i]->stop)) {
				$num = 2;
				for ($j = $i+1; $j<$s; $j++) {
					if (isset($positions[$j]->stop)) {
						$positions[$i]->stop = $positions[$i-1]->stop->op_plus($positions[$j]->stop->op_minus($positions[$i-1]->stop))->op_div(new Sass_script_literals_SassNumber($num));
						break;
					}
					else
						$num += 1;
				}
			}
		}
		# normalize unitless numbers
		foreach ($positions as &$pos) {
			if ($pos->stop->isUnitless()) {
				$pos->stop = ($pos->stop->value <= 1 ?
					$pos->stop->op_times(new Sass_script_literals_SassNumber('100%')) :
					$pos->stop->op_times(new Sass_script_literals_SassNumber('1px'))
				);
			}
		}
		if ($positions[$s-1]->stop->op_eq(new Sass_script_literals_SassNumber('0px'))->toBoolean() ||
			 $positions[$s-1]->stop->op_eq(new Sass_script_literals_SassNumber('0%'))->toBoolean())
			 	throw new Sass_script_SassScriptFunctionException('Colour stops must be specified in increasing order', array(), Sass_script_SassScriptParser::$context->node);
		return null;
	}
}