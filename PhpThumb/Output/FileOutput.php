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
 * Save the image as a local file
 * 
 * If filepath, is a directory, the class will generate a unique filename.
 * 
 * If 'addExtension' option is true, it will automatically add file extension 
 * provided by the processor
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
class FileOutput implements OutputInterface
{
	
	/**
	 * The filepath for saving the image
	 * @var string
	 */
	protected $_filepath = null;
	
	/**
	 * Options array
	 * @var array
	 */
	protected $_options = array(
		'addExtension' => false
	);
	
	/**
	 * Path to file of saved image
	 * @var string
	 */
	protected $_file = '';
	
	/**
	 * MimeType of saved image
	 * @var string
	 */
	protected $_mimeType = '';
	
	/**
	 * Width of saved image
	 * @var int
	 */
	protected $_width = 0;
	
	/**
	 * Height of saved image
	 * @var int
	 */
	protected $_height = 0;
	
	/**
	 * Crate a new FileOutput instance
	 * 
	 * @param string $filepath
	 * @param array $options
	 */
	public function __construct($filepath, array $options = array())
	{
		$this->_filepath = $filepath;
		$this->_options = array_merge($this->_options, $options);
	}

	/**
	 * (non-PHPdoc)
	 * @see PhpThumb\Output.OutputInterface::save()
	 */
	public function save(ProcessorInterface $processor)
	{
		$data = $processor->__toString();
		
		$filename = '';

		//If is dir, generate unique filename
		if(is_dir($this->_filepath))
		{
			$filename .= (substr($this->_filepath, 0,-1) != '/' ? '/' : '');
			while(file_exists($this->_filepath . $filename))
			{
				$filename .= md5(uniqid(rand(), true));
				if($this->_options['addExtension'] === true)
					$filename .= $processor->getFileExtension();
			}
		}
		else
		{
			if($this->_options['addExtension'] === true)
				$filename .= $processor->getFileExtension();
		}

		file_put_contents($this->_filepath . $filename, $data);
		
		$this->_file = $this->_filepath . $filename;
		$this->_mimeType = $processor->getMimeType();
		$this->_width = $processor->getWidth();
		$this->_height = $processor->getHeight();
		
		return $this;
	}
	
	/**
	 * Get the path to the saved file after run
	 * 
	 * @return string
	 */
	public function getFile()
	{
		return $this->_file;
	}
	
	/**
	 * Get the mimetype of the saved file after run
	 * 
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->_mimeType;
	}
	
	/**
	 * Get the width in pixles of saved file after run
	 * 
	 * @return string
	 */
	public function getWidth()
	{
		return $this->_width;
	}
	
	/**
	 * Get the height in pixles of saved file after run
	 * 
	 * @return string
	 */
	public function getHeight()
	{
		return $this->_height;
	}
	
}