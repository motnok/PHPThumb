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

namespace PhpThumb\Processor;

use PhpThumb\Geometry\FlipableInterface;
use PhpThumb\Geometry\RotatableInterface;
use PhpThumb\Geometry\ResizableInterface;
use PhpThumb\Geometry\CropableInterface;
use PhpThumb\Input\InputInterface;

/**
 * Processor using GDLib
 * 
 * This class implements methods, for working with the image using GDLib.
 * 
 * By implementing CropableInterface, ResizableInterface, RotatableInterface and 
 * FlipableInterface, we tell our plugins, that this class can handle a crop, 
 * resize, rotate and a flip.
 * 
 * By having these interfaces, it should be easy to create multiple processor 
 * classes, implementing the same basic methods, and having the plugins do all 
 * the calculations.
 * This means - in theory - we can use the same plugins on different processor 
 * classes.
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
class GdLibProcessor implements ProcessorInterface, CropableInterface, 
								ResizableInterface, RotatableInterface, 
								FlipableInterface
{
	/**
	 * The immge we are working from
	 * 
	 * @var unknown_type
	 */
	protected $_image;
	
	/**
	 * The imagetype of current image
	 * 
	 * Using the constants defined by GDLib
	 * 
	 * @example http://www.php.net/manual/en/function.image-type-to-mime-type.php
	 * @var int
	 */
	protected $_imageType;
	
	/**
	 * Array of optional options for processing
	 * 
	 * @var array
	 */
	protected $_options = array(
		'preserveAlpha' => true,
		'jpegQuality' => 80
	);
	
	/**
	 * Temporary pointer for image when any work is done
	 *  
	 * @var unknown_type
	 */
	protected $_workingImage;
	
	/**
	 * Create an new instance of GdLibProcessor
	 * 
	 * Availabe options are:
	 * 	preserveAlpha:	alpha channel be preserved on PNG/GIF images
	 * 	jpegQuality:	Quality of JPEG images from 0 - 100 
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
		$this->_options = array_merge($this->_options, $options);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Processor.ProcessorInterface::setInput()
	 */
	public function setInput(InputInterface $input)
	{
		$this->_image = imagecreatefromstring($input->getFile());
		$this->_imageType = $input->getFileType();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Processor.ProcessorInterface::getWidth()
	 */
	public function getWidth()
	{
		return imagesx($this->_image);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Processor.ProcessorInterface::getHeight()
	 */
	public function getHeight()
	{
		return imagesy($this->_image);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Geometry.RotatableInterface::rotate()
	 */
	public function rotate($degrees)
	{
		if (!function_exists('imagerotate'))
			throw new RuntimeException('Your version of GD does not support image rotation.');

		$rotate = imagerotate($this->_image, $degrees, 0);
		$this->_image = $rotate;
		unset($rotate);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Geometry.CropableInterface::crop()
	 */
	public function crop($x, $y, $width, $height)
	{
		// validate input
		if (!is_numeric($x))
			throw new \InvalidArgumentException('$x must be numeric');
		
		if (!is_numeric($y))
			throw new \InvalidArgumentException('$y must be numeric');
		
		if (!is_numeric($width))
			throw new \InvalidArgumentException('$width must be numeric');
		
		if (!is_numeric($height))
			throw new \InvalidArgumentException('$height must be numeric');
		
		// create the working image
		if (function_exists('imagecreatetruecolor'))
		{
			$this->_workingImage = imagecreatetruecolor($width, $height);
		}
		else
		{
			$this->_workingImage = imagecreate($width, $height);
		}
		
		$this->_preserveAlpha();
		
		imagecopyresampled
		(
			$this->_workingImage,
			$this->_image,
			0,
			0,
			$x,
			$y,
			$width,
			$height,
			$width,
			$height
		);
		
		$this->_image = $this->_workingImage;
		unset($this->_workingImage);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Geometry.ResizableInterface::resize()
	 */
	public function resize($width, $height)
	{
		if (!is_numeric($width))
			throw new \InvalidArgumentException('$width must be numeric');
		
		if (!is_numeric($height))
			throw new \InvalidArgumentException('$height must be numeric');
			
		// create the working image
		if (function_exists('imagecreatetruecolor'))
		{
			$this->_workingImage = imagecreatetruecolor($width, $height);
		}
		else
		{
			$this->_workingImage = imagecreate($width, $height);
		}
		
		$this->_preserveAlpha();		
		
		// and create the newly sized image
		imagecopyresampled
		(
			$this->_workingImage,
			$this->_image,
			0,
			0,
			0,
			0,
			$width,
			$height,
			imagesx($this->_image),
			imagesy($this->_image)
		);
		
		$this->_image = $this->_workingImage;
		unset($this->_workingImage);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Geometry.FlipableInterface::flip()
	 */
	public function flip($direction)
	{
		$width = imagesx($this->_image);
		$height = imagesy($this->_image);
		
		$this->_workingImage = imagecreatetruecolor($width, $height);
		
		$success = false;
		
		switch ($direction)
		{
			case self::DIRECTION_HORIZONTAL:
				$success = imagecopyresampled(
					$this->_workingImage, 
					$this->_image, 
					0, 
					0, 
					($width-1), 
					0, 
					$width, 
					$height, 
					(0-$width),
					$height
				);
				break;
			case self::DIRECTION_VERTICAL:
				$success = imagecopyresampled(
					$this->_workingImage, 
					$this->_image, 
					0, 
					0, 
					0, 
					($height-1), 
					$width, 
					$height, 
					$width, 
					(0-$height)
				);
				break;
		}
		
		if($success)
			$this->_image = $this->_workingImage;
		unset($this->_workingImage);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Processor.ProcessorInterface::getMimeType()
	 */
	public function getMimeType()
	{
		return image_type_to_mime_type($this->_imageType);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Processor.ProcessorInterface::getFileExtension()
	 */
	public function getFileExtension()
	{
		return image_type_to_extension($this->_imageType);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Processor.ProcessorInterface::__toString()
	 */
	public function __toString()
	{
		ob_start();
		switch ($this->_imageType)
		{
			case IMAGETYPE_GIF:
				imagegif($this->_image);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($this->_image, null, $this->_options['jpegQuality']);
				break;
			case IMAGETYPE_PNG:
			default: //If string
				imagejpeg($this->_image);
				break;
		}
		return ob_get_clean();
	}
	
	/**
	 * Preserves the alpha or transparency for PNG and GIF files
	 * 
	 * Alpha / transparency will not be preserved if the appropriate options are set to false.
	 * Also, the GIF transparency is pretty skunky (the results aren't awesome), but it works like a 
	 * champ... that's the nature of GIFs tho, so no huge surprise.
	 * 
	 * This functionality was originally suggested by commenter Aimi (no links / site provided) - Thanks! :)
	 *   
	 */
	protected function _preserveAlpha()
	{
		if ($this->_imageType == IMAGETYPE_PNG && $this->_options['preserveAlpha'] === true)
		{
			imagealphablending($this->_workingImage, false);
			
			$colorTransparent = imagecolorallocatealpha
			(
				$this->_workingImage, 
				$this->options['alphaMaskColor'][0], 
				$this->options['alphaMaskColor'][1], 
				$this->options['alphaMaskColor'][2], 
				0
			);
			
			imagefill($this->_workingImage, 0, 0, $colorTransparent);
			imagesavealpha($this->_workingImage, true);
		}
		// preserve transparency in GIFs... this is usually pretty rough tho
		if ($this->_imageType == IMAGETYPE_GIF && $this->_options['preserveTransparency'] === true)
		{
			$colorTransparent = imagecolorallocate
			(
				$this->_workingImage, 
				$this->options['transparencyMaskColor'][0], 
				$this->options['transparencyMaskColor'][1], 
				$this->options['transparencyMaskColor'][2] 
			);
			
			imagecolortransparent($this->_workingImage, $colorTransparent);
			imagetruecolortopalette($this->_workingImage, true, 256);
		}
	}
	
}