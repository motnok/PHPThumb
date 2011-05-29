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

use PhpThumb\Geometry\CropableInterface;
use PhpThumb\Processor\ProcessorInterface;

/**
 * Vanilla Cropping
 * 
 * Crops from x,y with specified width and height
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
class CropPlugin implements PluginInterface
{

	/**
	 * Options array
	 * 
	 * @var array
	 */
	protected $_options = array(
		'x' => 0,
		'y' => 0,
		'width' => 1,
		'height' => 1
	);
	
	/**
	 * Create a new CropPlugin instance
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
		if(!$current instanceof CropableInterface) 
			return;
			
		// validate input
		if (!is_numeric($this->_options['x']))
			throw new InvalidArgumentException('Parameter "x" must be numeric');
		
		if (!is_numeric($this->_options['y']))
			throw new InvalidArgumentException('Parameter "y" must be numeric');
		
		if (!is_numeric($this->_options['width']))
			throw new InvalidArgumentException('Paramter "width" must be numeric');
		
		if (!is_numeric($this->_options['height']))
			throw new InvalidArgumentException('Paramter "height" must be numeric');
		
			
		$x = $this->_options['x'];
		$y = $this->_options['y'];
		$width	= ($current->getWidth() < $this->_options['width']) ? $current->getWidth() : $this->_options['width'];
		$height = ($current->getHeight() < $this->_options['height']) ? $current->getHeight() : $this->_options['height'];
		
		// ensure everything's in bounds
		if (($x + $width) > $current->getWidth())
			$x = ($current->getWidth() - $width);
		
		if (($y + $height) > $current->getHeight())
			$y = ($current->getWidth() - $height);
		
		if ($x < 0) 
			$x = 0;
		
	    if ($y < 0) 
			$y = 0;
			
		$current->crop($x, $y, $width, $height);
	}
	
}