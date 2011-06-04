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

namespace PhpThumb\Plugin;

use PhpThumb\Geometry\RotatableInterface;
use PhpThumb\Processor\ProcessorInterface;

/**
 * Rotate the image 
 * 
 * Rotates the image clockwise, by degrees parameter defined in options
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
class RotatePlugin implements PluginInterface
{

	/**
	 * Options array
	 * 
	 * @var array
	 */
	protected $_options = array(
		'degrees' => 0
	);
	
	/**
	 * Create a new RotatePlugin instance
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
		$this->_options = array_merge($this->_options, $options); 
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Plugin.PluginInterface::run()
	 */
	public function run(ProcessorInterface $current)
	{
		if(!$current instanceof RotatableInterface) 
			return;
			
		// make sure our arguments are valid
		if (!is_numeric($this->_options['degrees']))
			throw new \InvalidArgumentException('Paremeter "degrees" must be numeric');
		
		$current->rotate($this->_options['degrees']);
	}
	
}