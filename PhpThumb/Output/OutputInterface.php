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

namespace PhpThumb\Output;

use PhpThumb\Processor\ProcessorInterface;

/**
 * OutputInterface
 * 
 * Implements method for saving the image. 
 * 
 * It is up to the output class where the file is saved.
 * Should make it easy to provide classes for saving images to different 
 * locations, like Amazon S3, ftp or just a local file
 *  
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
interface OutputInterface
{
	
	/**
	 * Save the image from the given processor
	 * 
	 * This method should save the image when this method is called, and return
	 * self 
	 * 
	 * @param ProcessorInterface $processor
	 * @return ProcessorInterface
	 */
	public function save(ProcessorInterface $processor);
	
}