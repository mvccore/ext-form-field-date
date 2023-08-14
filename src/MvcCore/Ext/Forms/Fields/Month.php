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
 *                 with type `month` to select month number in year. `Month` 
 *                 field has it's own validator to check submitted value 
 *                 format/min/max/step and dangerous characters in 
 *                 submitted month value.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Month extends \MvcCore\Ext\Forms\Fields\Date {

	/**
	 * String format mask to format given values in `\DateTimeInterface` type for PHP `date_format()` function or 
	 * string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m"` for value like: `"2014-18"`.
	 * @var string
	 */
	protected static $defaultFormat = 'Y-m';

	/**
	 * Possible values: `month`.
	 * @var string
	 */
	protected $type = 'month';

	/**
	 * Validators: 
	 * - `Month` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var \string[]|\Closure[]
	 */
	protected $validators = ['Month'];
	
	/**
	 * Round typed value into proper date/datetime value to be possible 
	 * to compare server and user input values correctly later in submit.
	 * @param  \DateTime|\DateTimeImmutable $value
	 * @return \DateTime|\DateTimeImmutable
	 */
	public function RoundValue ($value) {
		$rounded = clone $value;
		$years = intval($value->format('Y'));
		$months = intval($value->format('n'));
		$rounded->setDate($years, $months, 1);
		return $rounded->setTime(0, 0, 0, 0);
	}

}
