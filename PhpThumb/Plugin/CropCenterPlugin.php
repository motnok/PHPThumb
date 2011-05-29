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
 * Crops an image from the center
 * 
 * If no 'height' options is given, the 'width' options will be used as a 
 * height, thus creating a square crop.
 * This is also the case vice versa.
 * 
 * @copyright Copyright (c) 2011, Kristoffer Hansen
 */
class CropCenterPlugin extends ResizeAbstract implements PluginInterface
{

	/**
	 * Options array
	 * 
	 * @var array
	 */
	protected $_options = array(
		'width' => null,
		'height' => null
	);
	
	/**
	 * Create a new CropCenterPlugin instance
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
			
		$width = $this->_options['width'];
		$height = $this->_options['height'];
		
		if(is_null($width) && is_null($height))
			throw new InvalidArgumentException('Either "width" or "height" paramter must be defined');
			
		if ($width !== null && !is_numeric($width))
			throw new InvalidArgumentException('Parameter "width" must be numeric');
		
		if ($height !== null && !is_numeric($height))
			throw new InvalidArgumentException('Parameter "height" must be numeric');
		
		if (is_null($height))
		{
			$height = $width;
		}
		else if(is_null($width))
		{
			$width = $height;
		}
		
		$width = ($current->getWidth() < $width) ? $current->getWidth() : $width;
		$height = ($current->getHeight() < $height) ? $current->getHeight() : $height;
		
		$x = intval(($current->getWidth() - $width) / 2);
		$y = intval(($current->getHeight() - $height) / 2);
			
		$current->crop($x, $y, $width, $height);
	}
	
}