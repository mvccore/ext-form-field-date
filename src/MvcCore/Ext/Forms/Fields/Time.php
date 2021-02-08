<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, pre-dispatch and render `<input>` HTML element 
 *				   with type `time` to select time in day. `Time` 
 *				   field has it's own validator to check submitted value 
 *				   format/min/max/step and dangerous characters in 
 *				   submitted time value.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Time extends \MvcCore\Ext\Forms\Fields\Date {

	/**
	 * String format mask to format given values in `\DateTimeInterface` type for PHP `date_format()` function or 
	 * string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"H:i"` for value like: `"22:15"`.
	 * @var string
	 */
	protected static $defaultFormat = 'H:i'; // 22:15

	/**
	 * Possible values: `time`.
	 * @var string
	 */
	protected $type = 'time';
	
	/**
	 * Validators: 
	 * - `Time` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Time'];
}
