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
use PhpThumb\Processor\ProcessorInterface;

/**
 * PluginInterface
 * 
 * Plugins must implement this interface.
 * 
 * @author Kristoffer Hansen <kriz@motnok.dk>
 */
interface PluginInterface
{

	/**
	 * Run this plugin
	 * 
	 * By providing the processor, it is up to the plugin, to determine if the 
	 * processor is valid for the this plugin, by checking for correct 
	 * interface.
	 * 
	 * This method can run the correct basic function on the processor if it is
	 * defined by the interface it implements.
	 * 
	 * @param ProcessorInterface $current
	 */
	public function run(ProcessorInterface $current);

}