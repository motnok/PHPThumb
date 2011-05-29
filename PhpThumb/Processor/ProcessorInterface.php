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

use PhpThumb\Input\InputInterface;

/**
 * ProcessorInterface
 * 
 * A processor, handles image alterations for input provided by setInput method.
 * 
 * It also returns basic information about the image, and the image as a string.
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
interface ProcessorInterface
{

	/**
	 * Set the input to work with
	 * 
	 * @param InputInterface $input
	 */
	public function setInput(InputInterface $input);
	
	/**
	 * Get the image as a raw string
	 * 
	 * @return string
	 */
	public function __toString();
	
	/**
	 * Get the mime type of the current image
	 * 
	 * @return string
	 */
	public function getMimeType();
	
	/**
	 * Get the file extension of current image with . (dot)
	 * 
	 * @return string
	 */
	public function getFileExtension();
	
	/**
	 * Get the width in pixels of current image
	 * 
	 * @return int
	 */
	public function getWidth();
	
	/**
	 * Get height in pixles of current image
	 * 
	 * @return int
	 */
	public function getHeight();
	
}