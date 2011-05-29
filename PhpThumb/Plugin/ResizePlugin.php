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

use PhpThumb\Geometry\ResizableInterface;
use PhpThumb\Processor\ProcessorInterface;

/**
 * Resizes an image to be no larger than 'width' or 'height' option
 * 
 * If either param is set to zero, then that dimension will not be considered as a part of the resize.
 * Additionally, if 'resizeUp' is set to true (false by default), then this function will
 * also scale the image up to the maximum dimensions provided.
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 * @todo resize plugins contain a bunch of redundant code for basic 
 */
class ResizePlugin extends ResizeAbstract implements PluginInterface
{

	/**
	 * Options array
	 * 
	 * @var array
	 */
	protected $_options = array(
		'width' => 0,
		'height' => 0,
		'resizeUp' => true
	);
	
	/**
	 * Crate a new ResizePlugin instance
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
			
		// make sure our arguments are valid
		if (!is_numeric($this->_options['width']))
			throw new InvalidArgumentException('Paremeter "width" must be numeric');
		
		if (!is_numeric($this->_options['height']))
			throw new InvalidArgumentException('Paremeter "height" must be numeric');
		
		// make sure we're not exceeding our image size if we're not supposed to
		if ($this->_options['resizeUp'] === false)
		{
			$maxHeight	= (intval($this->_options['height']) > $current->getHeight()) ? $current->getHeight() : $this->_options['height'];
			$maxWidth	= (intval($this->_options['width']) > $current->getWidth()) ? $current->getWidth() : $this->_options['width'];
		}
		else
		{
			$maxHeight	= intval($this->_options['height']);
			$maxWidth	= intval($this->_options['width']);
		}
		
		// get the new dimensions...
		$dimensions = $this->_calcImageSize($current->getWidth(), $current->getHeight(), $maxWidth, $maxHeight);
		
		$current->resize($dimensions['width'], $dimensions['height']);
	}
	
}