<?php
/* SVN FILE: $Id: SassBoolean.php 49 2010-04-04 10:51:24Z chris.l.yates $ */
/**
 * Compass extension SassScript font files functions class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
 
/**
 * Compass extension SassScript font files functions class.
 * A collection of functions for use in SassSCript.
 * @package			PHamlP
 * @subpackage	Sass.extensions.compass.functions
 */
class SassExtentionsCompassFunctionsFontFiles {
	public function font_files() {
		if (func_num_args() % 2)
			throw new Sass_script_SassScriptFunctionException('An even number of arguments must be passed to font_files()', array(), Sass_script_SassScriptParser::$context->node);

		$args = func_get_args();
		$files = array();
		while ($args) {
			$files[] = '#{font_url('.array_shift($args)."} format('".array_shift($args)."')";
		}
		return new Sass_script_literals_SassString(join(", ", $files));
	}
}