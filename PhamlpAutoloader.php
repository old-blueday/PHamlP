<?php

if (class_exists('PHPUnit_Framework_TestCase'))
{
	PhamlpAutoloader::register();
}

class PhamlpAutoloader
{
	/**
	 * Registers sfTemplateAutoloader as an SPL autoloader.
	 */
	static public function register()
	{
		//ini_set('unserialize_callback_func', 'spl_autoload_call');
		spl_autoload_register(array(new self, 'autoload'));
	}

	static public function unregister()
	{
		spl_autoload_unregister(array(new self, 'autoload'));
	}

	/**
	 * Handles autoloading of classes.
	 *
	 * @param  string  $class  A class name.
	 *
	 * @return boolean Returns true if the class has been loaded
	 */
	public function autoload($class)
	{
		if (0 === strpos($class, 'Phamlp') || 0 === strpos($class, 'Sass'))
		{
			$_class = str_replace('_', '/', $class);
			require __DIR__ . '/' . $_class . '.php';
		}
		else
		{
			return false;
		}

		if (!class_exists($class) && !interface_exists($class) && (!function_exists('trait_exists') || !trait_exists($class))) {
            throw new InvalidArgumentException(sprintf('Unable to load class "%s"', $class));
        }

		return true;
	}
}
