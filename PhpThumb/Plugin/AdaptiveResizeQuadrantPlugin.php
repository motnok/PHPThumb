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
 * Adaptively Resizes the Image and Crops Using a Quadrant
 *
 * This function attempts to get the image to as close to the provided dimensions as possible, and then crops the
 * remaining overflow using the quadrant to get the image to be the size specified.
 *
 * The quadrants available are Top, Bottom, Center, Left, and Right:
 *
 * +---+---+---+
 * |   | T |   |
 * +---+---+---+
 * | L | C | R |
 * +---+---+---+
 * |   | B |   |
 * +---+---+---+
 *
 * Note that if your image is Landscape and you choose either of the Top or Bottom quadrants (which won't
 * make sence since only the Left and Right would be available, then the Center quadrant will be used
 * to crop. This would have exactly the same result as using adaptiveResize().
 * The same goes if your image is portrait and you choose either the Left or Right quadrants.
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 * @todo resize plugins contain a bunch of redundant code for basic 
 */
class AdaptiveResizeQuadrantPlugin extends ResizeAbstract implements PluginInterface
{

	/**
	 * Constant for left quadrant position
	 * @var string
	 */
	const POSITION_LEFT = 'L';
	
	/**
	 * Constant for right quadrant position
	 * @var string
	 */
	const POSITION_RIGHT = 'R';
	
	/**
	 * Constant for center quadrant position
	 * @var string
	 */
	const POSITION_CENTER = 'C';

	/**
	 * Constant for top quadrant position
	 * @var string
	 */
	const POSITION_TOP = 'T';

	/**
	 * Constant for bottom quadrant position
	 * @var string
	 */
	const POSITION_BOTTOM = 'B';
	
	/**
	 * Options array
	 * 
	 * @var array
	 */
	protected $_options = array(
		'width' => 0,
		'height' => 0,
		'position' => self::POSITION_CENTER,
		'resizeUp' => true
	);
	
	/**
	 * Create a new AdaptiveResizeQuadrantPlugin instance
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
		
		// Crop the rest of the image using the quadrant
		if ($current->getWidth() > $maxWidth)
		{
		    // Image is landscape
		    switch ($this->_options['position']) {
		        case self::POSITION_LEFT:
		            $cropX = 0;
		            break;

		        case self::POSITION_RIGHT:
		            $cropX = intval(($current->getWidth() - $maxWidth));
		            break;

		        case self::POSITION_CENTER:
		        default:
		            $cropX = intval(($current->getWidth() - $maxWidth) / 2);
		            break;
		    }


		} 
		elseif ($current->getHeight() > $maxHeight)
		{
		    // Image is portrait
			switch ($this->_options['position']) {
		        case self::POSITION_TOP:
		            $cropY = 0;
		            break;

		        case self::POSITION_BOTTOM:
		            $cropY = intval(($current->getHeight() - $maxHeight));
		            break;

		        case self::POSITION_CENTER:
		        default:
		            $cropY = intval(($current->getHeight() - $maxHeight) / 2);
		            break;
		    }

		}
		
		$current->crop($cropX, $cropY, $cropWidth, $cropHeight);
	}
}