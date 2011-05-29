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
 * Get input from a local file, http or string
 *
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
class FileInput implements InputInterface
{
	
	/**
	 * Path to file
	 * @var string
	 */
	protected $_filename = '';
	
	/**
	 * Is $_filename a string of image data?
	 * @var bool
	 */
	protected $_isDataStream = false;
	
	/**
	 * Is $_filename a remote image?
	 * @var bool
	 */
	protected $_remoteImage = false;
	
	/**
	 * Create an new FileInput instance
	 * 
	 * @param string $filename
	 * @param bool $isDataStream is filename a string of data
	 */
	public function __construct($filename, $isDataStream = false)
	{
		$this->_filename = $filename;
		$this->_isDataStream = (bool) $isDataStream;
		
		if (preg_match('/https?:\/\//', $this->_filename) !== 0)
			$this->_remoteImage = true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Input.InputInterface::getFile()
	 */
	public function getFile()
	{
		if ($this->_isDataStream === true)
			return $this->_filename;
			
		if ($this->_remoteImage)
			return file_get_contents($this->_filename);
		
		if (!file_exists($this->_fileName))
		{
			throw new \Exception('Image file not found: ' . $this->_fileName);
		}
		elseif (!is_readable($this->_fileName))
		{
			throw new \Exception('Image file not readable: ' . $this->_fileName);
		}
		
		return file_get_contents($this->_filename);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Input.InputInterface::getFileType()
	 */
	public function getFileType()
	{
		if($this->_isDataStream === true)
			return self::IMAGETYPE_STRING;
		
		$formatInfo = getimagesize($this->_filename);
		
		// non-image files will return false
		if ($formatInfo === false)
		{
			if ($this->_remoteImage)
			{
				throw new Exception('Could not determine format of remote image: ' . $this->fileName);
			}
			else
			{
				throw new Exception('File is not a valid image: ' . $this->fileName);
			}
		}
		
		$mimeType = isset($formatInfo['mime']) ? $formatInfo['mime'] : null;
		
		switch ($formatInfo[2])
		{
			case IMAGETYPE_GIF:
			case IMAGETYPE_PNG:
			case IMAGETYPE_JPEG:
				return $formatInfo[2];
				break;
			default:
				throw new Exception('Image format not supported: ' . $mimeType);
		}
	}
	
}