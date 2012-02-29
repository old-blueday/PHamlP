<?php
/* SVN FILE: $Id$ */
/**
 * Sass_tree_SassWhileNode class file.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/**
 * Sass_tree_SassWhileNode class.
 * Represents a Sass @while loop and a Sass @do loop.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassWhileNode extends Sass_tree_SassNode {
	const MATCH = '/^@(do|while)\s+(.+)$/i';
	const LOOP = 1;
	const EXPRESSION = 2;
	const IS_DO = 'do';
	/**
	 * @var boolean whether this is a do/while.
	 * A do/while loop is guarenteed to run at least once.
	 */
	protected $isDo;
	/**
	 * @var string expression to evaluate
	 */
	protected $expression;

	/**
	 * Sass_tree_SassWhileNode constructor.
	 * @param object source token
	 * @return Sass_tree_SassWhileNode
	 */
	public function __construct($token) {
		parent::__construct($token);
		preg_match(self::MATCH, $token->source, $matches);
		$this->expression = $matches[self::EXPRESSION];
		$this->isDo = ($matches[self::LOOP] === Sass_tree_SassWhileNode::IS_DO);
	}

	/**
	 * Parse this node.
	 * @param Sass_tree_SassContext the context in which this node is parsed
	 * @return array the parsed child nodes
	 */
	public function parse($context) {
		$children = array();
		if ($this->isDo) {
			do {
				$children = array_merge($children, $this->parseChildren($context));
			} while ($this->evaluate($this->expression, $context)->toBoolean());
		}
		else {
			while ($this->evaluate($this->expression, $context)->toBoolean()) {
				$children = array_merge($children, $this->parseChildren($context));
			}
		}
		return $children;
	}
}