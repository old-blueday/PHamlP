<?php
/* SVN FILE: $Id$ */
/**
 * Sass_tree_SassIfNode class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Sass_tree_SassIfNode class.
 * Represents Sass If, Else If and Else statements.
 * Else If and Else statement nodes are chained below the If statement node.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassIfNode extends Sass_tree_SassNode {
	const MATCH_IF = '/^@if\s+(.+)$/i';
	const MATCH_ELSE = '/@else(\s+if\s+(.+))?/i';
	const IF_EXPRESSION = 1;
	const ELSE_IF = 1;
	const ELSE_EXPRESSION = 2;
	/**
	 * @var Sass_tree_SassIfNode the next else node.
	 */
	protected $else;
	/**
	 * @var string expression to evaluate
	 */
	protected $expression;

	/**
	 * Sass_tree_SassIfNode constructor.
	 * @param object source token
	 * @param boolean true for an "if" node, false for an "else if | else" node
	 * @return Sass_tree_SassIfNode
	 */
	public function __construct($token, $if=true) {
		parent::__construct($token);
		if ($if) {
			preg_match(self::MATCH_IF, $token->source, $matches);
			$this->expression = $matches[Sass_tree_SassIfNode::IF_EXPRESSION];
		}
		else {
			preg_match(self::MATCH_ELSE, $token->source, $matches);
			$this->expression = (sizeof($matches)==1 ? null : $matches[Sass_tree_SassIfNode::ELSE_EXPRESSION]);
		}
	}

	/**
	 * Adds an "else" statement to this node.
	 * @param Sass_tree_SassIfNode "else" statement node to add
	 * @return Sass_tree_SassIfNode this node
	 */
	public function addElse($node) {
	  if (is_null($this->else)) {
	  	$node->parent	= $this->parent;
	  	$node->root		= $this->root;
			$this->else		= $node;
	  }
	  else {
			$this->else->addElse($node);
	  }
	  return $this;
	}

	/**
	 * Parse this node.
	 * @param Sass_tree_SassContext the context in which this node is parsed
	 * @return array parsed child nodes
	 */
	public function parse($context) {
		if ($this->isElse() || $this->evaluate($this->expression, $context)->toBoolean()) {
			$children = $this->parseChildren($context);
		}
		elseif (!empty($this->else)) {
			$children = $this->else->parse($context);
		}
		else {
			$children = array();
		}
		return $children;
	}

	/**
	 * Returns a value indicating if this node is an "else" node.
	 * @return true if this node is an "else" node, false if this node is an "if"
	 * or "else if" node
	 */
	protected function isElse() {
	  return empty($this->expression);
	}
}