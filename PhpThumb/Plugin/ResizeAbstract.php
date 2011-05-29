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

/**
 * Abstract base class for resize
 * 
 * Contains methods, used by multiple resize plugins
 * 
 * @copyright Copyright (c) 2011, Kristoffer Hansen
 * @abstract
 */
abstract class ResizeAbstract
{
	
	/**
	 * Calculates a new width and height for the image based on $maxWidth and the provided dimensions
	 * 
	 * @return array 
	 * @param int $width
	 * @param int $height
	 * @param int $maxWidth
	 */
	protected function _calcWidth ($width, $height, $maxWidth)
	{
		$newWidthPercentage	= (100 * $maxWidth) / $width;
		$newHeight			= ($height * $newWidthPercentage) / 100;
		
		return array
		(
			'width'		=> intval($maxWidth),
			'height'	=> intval($newHeight)
		);
	}
	
	/**
	 * Calculates a new width and height for the image based on $maxWidth and the provided dimensions
	 * 
	 * @return array 
	 * @param int $width
	 * @param int $height
	 * @param int $maxHeight
	 */
	protected function _calcHeight ($width, $height, $maxHeight)
	{
		$newHeightPercentage	= (100 * $maxHeight) / $height;
		$newWidth 				= ($width * $newHeightPercentage) / 100;
		
		return array
		(
			'width'		=> ceil($newWidth),
			'height'	=> ceil($maxHeight)
		);
	}
	
	/**
	 * Calculates the new image dimensions
	 * 
	 * These calculations are based on both the provided dimensions and $maxWidth and $maxHeight
	 * 
	 * @param int $width
	 * @param int $height
	 * @param int $maxWidth
	 * @param int $maxHeight
	 * @return array
	 */
	protected function _calcImageSize ($width, $height, $maxWidth = 0, $maxHeight = 0)
	{
		$newSize = array
		(
			'width'		=> $width,
			'height'	=> $height
		);
		
		if ($maxWidth > 0)
		{
			$newSize = $this->_calcWidth($width, $height, $maxWidth);
			
			if ($maxHeight > 0 && $newSize['height'] > $maxHeight)
			{
				$newSize = $this->_calcHeight($newSize['width'], $newSize['height'], $maxHeight);
			}
		}
		
		if ($maxHeight > 0)
		{
			$newSize = $this->_calcHeight($width, $height, $maxHeight);
			
			if ($maxWidth > 0 && $newSize['width'] > $maxWidth)
			{
				$newSize = $this->_calcWidth($newSize['width'], $newSize['height'], $maxWidth);
			}
		}
		
		return $newSize;
	}
	
	/**
	 * Calculates new image dimensions, not allowing the width and height to be less than either the max width or height 
	 * 
	 * @param int $width
	 * @param int $height
	 * @param int $maxWidth
	 * @param int $maxHeight
	 * @return array
	 */
	protected function _calcImageSizeStrict ($width, $height, $maxWidth, $maxHeight)
	{
		$newDimensions = array(
			'width'		=> $width,
			'height'	=> $height
		);
		
		// first, we need to determine what the longest resize dimension is..
		if ($maxWidth >= $maxHeight)
		{
			// and determine the longest original dimension
			if ($width > $height)
			{
				$newDimensions = $this->_calcHeight($width, $height, $maxHeight);
				
				if ($newDimensions['width'] < $maxWidth)
					$newDimensions = $this->_calcWidth($width, $height, $maxWidth);
			}
			elseif ($height >= $width)
			{
				$newDimensions = $this->_calcWidth($width, $height, $maxWidth);
				
				if ($newDimensions['height'] < $maxHeight)
					$newDimensions = $this->_calcHeight($width, $height, $maxHeight);
			}
		}
		elseif ($maxHeight > $maxWidth)
		{
			if ($width >= $height)
			{
				$newDimensions = $this->_calcWidth($width, $height, $maxWidth);
				
				if ($newDimensions['height'] < $maxHeight)
					$newDimensions = $this->_calcHeight($width, $height, $maxHeight);
			}
			elseif ($height > $width)
			{
				$newDimensions = $this->_calcHeight($width, $height, $maxWidth);
				
				if ($newDimensions['width'] < $maxWidth)
					$newDimensions = $this->_calcWidth($width, $height, $maxWidth);
			}
		}
		
		return $newDimensions;
	}
	
	/**
	 * Calculates new dimensions based on percent and the provided dimensions
	 * 
	 * @param int $percent
	 * @param int $width
	 * @param int $height
	 * @return array
	 */
	protected function _calcImageSizePercent ($percent, $width, $height)
	{
		if ($percent > 0)
		{
			$newWidth	= ($width * $percent) / 100;
			$newHeight	= ($height * $percent) / 100;
			
			return array 
			(
				'width'		=> ceil($newWidth),
				'height'	=> ceil($newHeight)
			);
		}
		
		return array 
		(
			'width'		=> $width,
			'height'	=> $height
		);
	}
	
}