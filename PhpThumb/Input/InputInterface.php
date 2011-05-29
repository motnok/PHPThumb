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

namespace PhpThumb\Input;

/**
 * InputInterface
 * 
 * Provides basic methods for getting input for processors, and should be 
 * implemented by alle input classes.
 * Should make it easy to get input from other locations, like Amazon S3, FTP or
 * a local file.
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
interface InputInterface
{
	
	/**
	 * Image type is a string
	 * 
	 * Identifies this image as a string, if image type is not determinable
	 * 
	 * @var string
	 * @todo is scope of this constant correct?
	 */
	const IMAGETYPE_STRING 	= 'string';
	
	/**
	 * Get the input file as a string
	 * 
	 * @return string
	 */
	public function getFile();
	
	/**
	 * Get the file type 
	 * 
	 * Returns the constant from getimagesize()
	 * @see http://php.net/manual/en/function.getimagesize.php
	 * @return int
	 */
	public function getFileType();
	
}