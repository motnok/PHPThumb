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
use PhpThumb\Geometry\ResizableInterface;
use PhpThumb\Processor\ProcessorInterface;

/**
 * Adaptively Resizes the Image and Crops Using a Percentage
 *
 * This function attempts to get the image to as close to the provided 
 * dimensions as possible, and then crops the remaining overflow using a 
 * provided percentage to get the image to be the size specified.
 *
 * The percentage mean different things depending on the orientation of the 
 * original image.
 *
 * For Landscape images:
 * ---------------------
 *
 * A percentage of 1 would crop the image all the way to the left, which would 
 * be the same as using AdaptiveResizeQuadrantPlugin with 
 * AdaptiveResizeQuadrantPlugin::POSITION_LEFT
 *
 * A percentage of 50 would crop the image to the center which would be the same
 * as using AdaptiveResizeQuadrantPlugin with 
 * AdaptiveResizeQuadrantPlugin::POSITION_CENTER, or even the original 
 * AdaptiveResizePlugin
 *
 * A percentage of 100 would crop the image to the image all the way to the 
 * right, etc, etc.
 * Note that you can use any percentage between 1 and 100.
 *
 * For Portrait images:
 * --------------------
 *
 * This works the same as for Landscape images except that a percentage of 1 
 * means top and 100 means bottom
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 * @todo resize plugins contain a bunch of redundant code for basic 
 */
class AdaptiveResizePercentPlugin extends ResizeAbstract implements PluginInterface
{

	/**
	 * Options array
	 * 
	 * @var array
	 */
	protected $_options = array(
		'width' => 0,
		'height' => 0,
		'percent' => 100,
		'resizeUp' => true
	);
	
	/**
	 * Create a new AdaptiveResizePercentPlugin instance
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
		if(!$current instanceof ResizableInterface) 
			return;
		if(!$current instanceof CropableInterface) 
			return;
			
		$width = $this->_options['width'];
		$height = $this->_options['height'];
		
			// make sure our arguments are valid
		if ((!is_numeric($width) || $width  == 0) && (!is_numeric($height) || $height == 0))
			throw new \InvalidArgumentException('Parameters "width" and "height" must be numeric and greater than zero');

		if (!is_numeric($width) || $width  == 0)
			$width = ( $height * $current->getWidth() ) / $current->getHeight();
		
		if (!is_numeric($height) || $height  == 0)
			$height = ( $width * $current->getHeight() ) / $current->getWidth();
			
		// make sure we're not exceeding our image size if we're not supposed to
		if ($this->_options['resizeUp'] === false)
		{
			$maxHeight	= (intval($height) > $current->getHeight()) ? $current->getHeight() : $height;
			$maxWidth	= (intval($width) > $current->getWidth()) ? $current->getWidth() : $width;
		}
		else
		{
			$maxHeight	= intval($height);
			$maxWidth	= intval($width);
		}
		
		// get the new dimensions...
		$dimensions = $this->_calcImageSizeStrict($current->getWidth(), $current->getHeight(), $maxWidth, $maxHeight);
		
		$current->resize($dimensions['width'], $dimensions['height']);
		
		//Lets crop
			
		$cropWidth	= $maxWidth;
		$cropHeight	= $maxHeight;
		$cropX 		= 0;
		$cropY 		= 0;

		$percent = $this->_options['percent'];
		if ($percent > 100) {
		    $percent = 100;
		} elseif ($percent < 1) {
		    $percent = 1;
		}

		if ($current->getWidth() > $maxWidth)
		{
		    // Image is landscape
		    $maxCropX = $current->getWidth() - $maxWidth;
		    $cropX = intval(($percent / 100) * $maxCropX);

		} elseif ($current->getHeight() > $maxHeight)
		{
		    // Image is portrait
		    $maxCropY = $current->getWidth() - $maxHeight;
		    $cropY = intval(($percent / 100) * $maxCropY);

		}
		
		$current->crop($cropX, $cropY, $cropWidth, $cropHeight);
	}
	
}