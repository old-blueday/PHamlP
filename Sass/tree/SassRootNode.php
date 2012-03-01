<?php
/* SVN FILE: $Id$ */
/**
 * Sass_tree_SassRootNode class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/*
require_once(dirname(__FILE__).'/../script/SassScriptParser.php');
require_once(dirname(__FILE__).'/../renderers/SassRenderer.php');
*/

/**
 * Sass_tree_SassRootNode class.
 * Also the root node of a document.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassRootNode extends Sass_tree_SassNode {
	/**
	 * @var Sass_script_SassScriptParser SassScript parser
	 */
	protected $script;
	/**
	 * @var Sass_renderers_SassRenderer the renderer for this node
	 */
	protected $renderer;
	/**
	 * @var Sass_SassParser
	 */
	protected $parser;
	/**
	 * @var array extenders for this tree in the form extendee=>extender
	 */
	protected $extenders = array();

	/**
	 * Root Sass_tree_SassNode constructor.
	 * @param Sass_SassParser Sass parser
	 * @return Sass_tree_SassNode
	 */
	public function __construct($parser) {
		parent::__construct((object) array(
			'source' => '',
			'level' => -1,
			'filename' => $parser->filename,
			'line' => 0,
		));
		$this->parser = $parser;
		$this->script = new Sass_script_SassScriptParser();
		$this->renderer = Sass_renderers_SassRenderer::getRenderer($parser->style);
		$this->root = $this;
	}

	/**
	 * Parses this node and its children into the render tree.
	 * Dynamic nodes are evaluated, files imported, etc.
	 * Only static nodes for rendering are in the resulting tree.
	 * @param Sass_tree_SassContext the context in which this node is parsed
	 * @return Sass_tree_SassNode root node of the render tree
	 */
	public function parse($context) {
		$node = clone $this;
		$node->children = $this->parseChildren($context);
		return $node;
	}

	/**
	 * Render this node.
	 * @return string the rendered node
	 */
	public function render() {
		$node = $this->parse(new Sass_tree_SassContext());
		$output = '';
		foreach ($node->children as $child) {
			$output .= $child->render();
		} // foreach
		return $output;
	}

	public function extend($extendee, $selectors) {
		$this->extenders[$extendee] = (isset($this->extenders[$extendee])
			? array_merge($this->extenders[$extendee], $selectors) : $selectors);
	}

	public function getExtenders() {
		return $this->extenders;
	}

	/**
	 * Returns a value indicating if the line represents this type of node.
	 * Child classes must override this method.
	 * @throws Sass_tree_SassNodeException if not overriden
	 */
	public static function isa($line) {
		throw new Sass_tree_SassNodeException('Child classes must override this method');
	}
}
