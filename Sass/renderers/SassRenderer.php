<?php
/* SVN FILE: $Id$ */
/**
 * Sass_renderers_SassRenderer class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */

require_once('SassCompactRenderer.php');
require_once('SassCompressedRenderer.php');
require_once('SassExpandedRenderer.php');
require_once('SassNestedRenderer.php');

/**
 * Sass_renderers_SassRenderer class.
 * @package			PHamlP
 * @subpackage	Sass.renderers
 */
class Sass_renderers_SassRenderer {
	/**#@+
	 * Output Styles
	 */
	const STYLE_COMPRESSED = 'compressed';
	const STYLE_COMPACT 	 = 'compact';
	const STYLE_EXPANDED 	 = 'expanded';
	const STYLE_NESTED 		 = 'nested';
	/**#@-*/

	const INDENT = '  ';

	/**
	 * Returns the renderer for the required render style.
	 * @param string render style
	 * @return Sass_renderers_SassRenderer
	 */
	public static function getRenderer($style) {
		switch ($style) {
			case self::STYLE_COMPACT:
		  	return new Sass_renderers_SassCompactRenderer();
			case self::STYLE_COMPRESSED:
		  	return new Sass_renderers_SassCompressedRenderer();
			case self::STYLE_EXPANDED:
		  	return new Sass_renderers_SassExpandedRenderer();
			case self::STYLE_NESTED:
		  	return new Sass_renderers_SassNestedRenderer();
		} // switch
	}
}