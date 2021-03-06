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
 * Resizes an image by a given percent uniformly
 * 
 * Option 'percent' should be whole number representation (i.e. 1-100)
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 * @todo resize plugins contain a bunch of redundant code for basic 
 */
class ResizePercentPlugin extends ResizeAbstract implements PluginInterface
{

	protected $_options = array(
		'percent' => 100
	);
	
	public function __construct(array $options = array())
	{
		$this->_options = array_merge($this->_options, $options); 
	}
	
	public function run(ProcessorInterface $current)
	{
		if(!$current instanceof ResizableInterface) 
			return;
			
		// make sure our arguments are valid
		if (!is_numeric($this->_options['percent']))
			throw new \InvalidArgumentException('Paremeter "percent" must be numeric');
		
		// get the new dimensions...
		$dimensions = $this->_calcImageSizePercent($this->_options['percent'], $current->getWidth(), $current->getHeight());
		$current->resize($dimensions['width'], $dimensions['height']);
		
	}
	
}