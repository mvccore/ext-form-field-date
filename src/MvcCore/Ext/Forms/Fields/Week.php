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
 *				   with type `week` to select week number in year. `Week` 
 *				   field has it's own validator to check submitted value 
 *				   format/min/max/step and dangerous characters in 
 *				   submitted week value.
 */
class Week extends \MvcCore\Ext\Forms\Fields\Date
{
	/**
	 * Possible values: `week`.
	 * @var string
	 */
	protected $type = 'week';

	/**
	 * String format mask to format given values in `\DateTimeInterface` type for PHP `date_format()` function or 
	 * string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"o-\WW"` for value like: `"2014-W30"`.
	 * @var string
	 */
	protected $format = 'o-\WW';
	
	/**
	 * Validators: 
	 * - `Week` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Week'];
}
