<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, predispatch and render `<input>` HTML element 
 *				   with type `datetime-local`. `DateTime` field has it's 
 *				   own validator to check format, min., max., step and 
 *				   dangerous characters in submitted date value.
 */
class DateTime extends \MvcCore\Ext\Forms\Fields\Date
{
	/**
	 * Possible values: `datetime-local`
	 * @var string
	 */
	protected $type = 'datetime-local';

	/**
	 * String format mask to format given values in `\DateTimeInterface` type for PHP `date_format()` function or 
	 * string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d\TH:i"` for value like: `"2014-03-17 22:15"`.
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @var string
	 */
	protected $format = 'Y-m-d\TH:i';
	
	/**
	 * Validators: 
	 * - `DateTime` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['DateTime'];
}
