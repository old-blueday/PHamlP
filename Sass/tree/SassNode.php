<?php
/* SVN FILE: $Id$ */
/**
 * Sass_tree_SassNode class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

require_once('SassContext.php');
require_once('SassCommentNode.php');
require_once('SassDebugNode.php');
require_once('SassDirectiveNode.php');
require_once('SassImportNode.php');
require_once('SassMixinNode.php');
require_once('SassMixinDefinitionNode.php');
require_once('SassPropertyNode.php');
require_once('SassRootNode.php');
require_once('SassRuleNode.php');
require_once('SassVariableNode.php');
require_once('SassExtendNode.php');
require_once('SassForNode.php');
require_once('SassIfNode.php');
require_once('SassElseNode.php');
require_once('SassWhileNode.php');
require_once('SassNodeExceptions.php');

/**
 * Sass_tree_SassNode class.
 * Base class for all Sass nodes.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassNode {
	/**
	 * @var Sass_tree_SassNode parent of this node
	 */
	protected $parent;
	/**
	 * @var Sass_tree_SassNode root node
	 */
	protected $root;
	/**
	 * @var array children of this node
	 */
	protected $children = array();
	/**
	 * @var object source token
	 */
	protected $token;
	
	/**
	 * Constructor.
	 * @param object source token
	 * @return Sass_tree_SassNode
	 */
	public function __construct($token) {
		$this->token = $token;
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
		throw new Sass_tree_SassNodeException('No getter function for {what}', array('{what}'=>$name), $this);
	}

	/**
	 * Setter.
	 * @param string name of property to set
	 * @return mixed value of property
	 * @return Sass_tree_SassNode this node
	 */
	public function __set($name, $value) {
		$setter = 'set' . ucfirst($name);
		if (method_exists($this, $setter)) {
			$this->$setter($value);
			return $this;
		}
		throw new Sass_tree_SassNodeException('No setter function for {what}', array('{what}'=>$name), $this);
	}

	/**
	 * Resets children when cloned
	 * @see parse
	 */
	public function __clone() {
		$this->children = array();
	}

	/**
	 * Return a value indicating if this node has a parent
	 * @return array the node's parent
	 */
	public function hasParent() {
		return !empty($this->parent);
	}

	/**
	 * Returns the node's parent
	 * @return array the node's parent
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Adds a child to this node.
	 * @return Sass_tree_SassNode the child to add
	 */
	public function addChild($child) {
		if ($child instanceof Sass_tree_SassElseNode) {
			if (!$this->lastChild instanceof Sass_tree_SassIfNode) {
				throw new Sass_SassException('@else(if) directive must come after @(else)if', array(), $child);
			}
			$this->lastChild->addElse($child);
		}
		else {
			$this->children[] = $child;
			$child->parent		= $this;
			$child->root			= $this->root;			
		}
		// The child will have children if a debug node has been added
		foreach ($child->children as $grandchild) {
			$grandchild->root = $this->root;		
		}
	}

	/**
	 * Returns a value indicating if this node has children
	 * @return boolean true if the node has children, false if not
	 */
	public function hasChildren() {
		return !empty($this->children);
	}

	/**
	 * Returns the node's children
	 * @return array the node's children
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * Returns a value indicating if this node is a child of the passed node.
	 * This just checks the levels of the nodes. If this node is at a greater
	 * level than the passed node if is a child of it.
	 * @return boolean true if the node is a child of the passed node, false if not
	 */
	public function isChildOf($node) {
		return $this->level > $node->level;
	}

	/**
	 * Returns the last child node of this node.
	 * @return Sass_tree_SassNode the last child node of this node
	 */
	public function getLastChild() {
	  return $this->children[count($this->children) - 1];
	}

	/**
	 * Returns the level of this node.
	 * @return integer the level of this node
	 */
	protected function getLevel() {
		return $this->token->level;
	}

	/**
	 * Returns the source for this node
	 * @return string the source for this node
	 */
	protected function getSource() {
		return $this->token->source;
	}

	/**
	 * Returns the debug_info option setting for this node
	 * @return boolean the debug_info option setting for this node
	 */
	protected function getDebug_info() {
		return $this->parser->debug_info;
	}

	/**
	 * Returns the line number for this node
	 * @return string the line number for this node
	 */
	protected function getLine() {
		return $this->token->line;
	}

	/**
	 * Returns the line_numbers option setting for this node
	 * @return boolean the line_numbers option setting for this node
	 */
	protected function getLine_numbers() {
		return $this->parser->line_numbers;
	}

	/**
	 * Returns vendor specific properties
	 * @return array vendor specific properties
	 */
	protected function getVendor_properties() {
		return $this->parser->vendor_properties;
	}

	/**
	 * Returns the filename for this node
	 * @return string the filename for this node
	 */
	protected function getFilename() {
		return $this->token->filename;
	}

	/**
	 * Returns the Sass parser.
	 * @return Sass_SassParser the Sass parser
	 */
	public function getParser() {
	  return $this->root->parser;
	}

	/**
	 * Returns the property syntax being used.
	 * @return string the property syntax being used
	 */
	public function getPropertySyntax() {
	  return $this->root->parser->propertySyntax;
	}

	/**
	 * Returns the SassScript parser.
	 * @return Sass_script_SassScriptParser the SassScript parser
	 */
	public function getScript() {
	  return $this->root->script;
	}

	/**
	 * Returns the renderer.
	 * @return Sass_renderers_SassRenderer the renderer
	 */
	public function getRenderer() {
	  return $this->root->renderer;
	}

	/**
	 * Returns the render style of the document tree.
	 * @return string the render style of the document tree
	 */
	public function getStyle() {
	  return $this->root->parser->style;
	}

	/**
	 * Returns a value indicating whether this node is in a directive
	 * @param boolean true if the node is in a directive, false if not
	 */
	public function inDirective() {
		return $this->parent instanceof Sass_tree_SassDirectiveNode ||
				$this->parent instanceof Sass_tree_SassDirectiveNode;
	}

	/**
	 * Returns a value indicating whether this node is in a SassScript directive
	 * @param boolean true if this node is in a SassScript directive, false if not
	 */
	public function inSassScriptDirective() {
		return $this->parent instanceof Sass_tree_SassForNode ||
				$this->parent->parent instanceof Sass_tree_SassForNode ||
				$this->parent instanceof Sass_tree_SassIfNode ||
				$this->parent->parent instanceof Sass_tree_SassIfNode ||
				$this->parent instanceof Sass_tree_SassWhileNode ||
				$this->parent->parent instanceof Sass_tree_SassWhileNode;
	}

	/**
	 * Evaluates a SassScript expression.
	 * @param string expression to evaluate
	 * @param Sass_tree_SassContext the context in which the expression is evaluated
	 * @return Sass_script_literals_SassLiteral value of parsed expression
	 */
	protected function evaluate($expression, $context, $x=null) {
		$context->node = $this;
		return $this->script->evaluate($expression, $context, $x);
	}

	/**
	 * Replace interpolated SassScript contained in '#{}' with the parsed value.
	 * @param string the text to interpolate
	 * @param Sass_tree_SassContext the context in which the string is interpolated
	 * @return string the interpolated text
	 */
	protected function interpolate($expression, $context) {
		$context->node = $this;
		return $this->script->interpolate($expression, $context);
	}
	
	/**
	 * Adds a warning to the node. 
	 * @param string warning message
	 * @param array line
	 */
	public function addWarning($message, $params=array()) {
		$warning = new Sass_tree_SassDebugNode($this->token, $message, $params);
		$this->addChild($warning);
	}

	/**
	 * Parse the children of the node.
	 * @param Sass_tree_SassContext the context in which the children are parsed
	 * @return array the parsed child nodes
	 */
	protected function parseChildren($context) {
		$children = array();
		foreach ($this->children as $child) {
			$children = array_merge($children, $child->parse($context));
		} // foreach
		return $children; 
	}

	/**
	 * Returns a value indicating if the token represents this type of node.
	 * @param object token
	 * @return boolean true if the token represents this type of node, false if not
	 */
	public static function isa($token) {
		throw new Sass_tree_SassNodeException('Child classes must override this method');
	}
}