<?php
/**
 * Namespaced PHP 5.3 refactoring of PhpThumb 3.0 
 * 
 * PhpThumb 3.0 is originally by Ian Selby/Gen X Design:
 * 	PhpThumb : PHP Thumb Library <http://phpthumb.gxdlabs.com>
 * 	Copyright (c) 2009, Ian Selby/Gen X Design
 * 	Author(s): Ian Selby <ian@gen-x-design.com>
 *
 * Copyright (c) 2011, Kristoffer Hansen
 * Author(s): Kristoffer Hansen <kriz@motnok.dk>
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author Ian Selby <ian@gen-x-design.com>
 * @author Kristoffer Hansen <kriz@motnok.dk>
 * @copyright Copyright (c) 2009, Ian Selby/Gen X Design
 * @copyright Copyright (c) 2011, Kristoffer Hansen
 * @link http://phpthumb.gxdlabs.com
 * @link https://github.com/motnok/PHPThumb
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 3.0n
 * @package PhpThumb
 * @filesource
 */

namespace PhpThumb;

use PhpThumb\Input\InputInterface;
use PhpThumb\Output\OutputInterface;
use PhpThumb\Plugin\PluginInterface;
use PhpThumb\Processor\ProcessorInterface;

/**
 * PhpThumb
 * 
 * Class for setting up processors, plugins, inputs and outputs
 * 
 * Basic usage sample:
 * <code>
 * $thumb = new \PhpThumb\PhpThumb(new \PhpThumb\Processor\GdLibProcessor());
 * $thumb->addPlugin(new \PhpThumb\Plugin\FlipPlugin())
 * 		 ->setInput(new \PhpThumb\Input\FileInput('path/to/some/image.png'))
 * 		 ->output(new \PhpThumb\Output\FileOutput('path/to/some/other/image.png'));
 * </code>
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
class PhpThumb
{
	
	/**
	 * Processor class for this instance
	 * 
	 * @var ProcessorInterface
	 */
	protected $_processor;
	
	/**
	 * Array of plugins to run on input
	 * 
	 * Plugins are run topdown
	 * 
	 * @var array
	 */
	protected $_plugins = array();
	
	/**
	 * Input class for this instance
	 * 
	 * @var InputInterface
	 */
	protected $_input = null;
	
	/**
	 * Setup our PhpThumb class
	 * 
	 * @param ProcessorInterface $processor
	 */
	public function __construct(ProcessorInterface $processor)
	{
		$this->_processor = $processor;
	}
	
	/**
	 * Add a plugin to to the plugin stack
	 * 
	 * @param PluginInterface $plugin
	 * @return PhpThumb
	 */
	public function addPlugin(PluginInterface $plugin)
	{
		$this->_plugins[] = $plugin;
		return $this;
	}
	
	/**
	 * Add multiple plugins
	 * 
	 * @param array $plugins
	 * @return PhpThumb
	 */
	public function addPlugins(array $plugins)
	{
		foreach($plugins as $plugin)
		{
			$this->addPlugin($plugin);
		}
		return $this;
	}
	
	/**
	 * Set input to use for this process
	 * 
	 * @param InputInterface $input
	 * @return PhpThumb
	 */
	public function setInput(InputInterface $input)
	{
		$this->_input = $input;
		return $this;
	}

	/**
	 * 
	 * Output image using the supplied output class
	 *
	 * @param OutputInterface $output
	 * @return OutputInterface
	 */
	public function output(OutputInterface $output)
	{
		if(is_null($this->_input))
			throw new \InvalidArgumentException('No input provided');
			
		$this->_processor->setInput($this->_input);
		
		foreach($this->_plugins as $plugin)
		{
			$plugin->run($this->_processor);
		}
		
		return $output->save($this->_processor);
	}
	
}