<?php
/* SVN FILE: $Id$ */
/**
 * Sass_tree_SassNode exception classes.
 * @author			Chris Yates <chris.l.yates@gmail.com>
 * @copyright 	Copyright (c) 2010 PBM Web Development
 * @license			http://phamlp.googlecode.com/files/license.txt
 * @package			PHamlP
 * @subpackage	Sass.tree
 */

/*
require_once(dirname(__FILE__).'/../SassException.php');
*/

/**
 * Sass_tree_SassNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassNodeException extends Sass_SassException {}

/**
 * Sass_tree_SassContextException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassContextException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassCommentNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassCommentNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassDebugNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassDebugNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassDirectiveNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassDirectiveNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassExtendNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassExtendNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassForNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassForNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassIfNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassIfNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassImportNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassImportNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassMixinDefinitionNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassMixinDefinitionNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassMixinNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassMixinNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassPropertyNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassPropertyNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassRuleNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassRuleNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassVariableNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassVariableNodeException extends Sass_tree_SassNodeException {}

/**
 * Sass_tree_SassWhileNodeException class.
 * @package			PHamlP
 * @subpackage	Sass.tree
 */
class Sass_tree_SassWhileNodeException extends Sass_tree_SassNodeException {}