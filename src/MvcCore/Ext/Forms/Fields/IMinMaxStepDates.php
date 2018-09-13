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
 * Responsibility: define getters and setters for field properties: `min`, 
 *				   `max` and `step`, for fields with type `date` and extended 
 *				   field types from `date` type.
 * Interface for classes:
 * - `\MvcCore\Ext\Forms\Fields\Date`
 *    - `\MvcCore\Ext\Forms\Fields\DateTime`
 *    - `\MvcCore\Ext\Forms\Fields\Month`
 *    - `\MvcCore\Ext\Forms\Fields\Time`
 *    - `\MvcCore\Ext\Forms\Fields\Week`
 * - `\MvcCore\Ext\Forms\Validators\Date`
 */
interface IMinMaxStepDates
{
    /**
	 * Get minimum value for `Date`, `Time`, `DateTime`, `Week` 
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2017-01-01"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "14:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2017-01-01 14:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2017-W01"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2017-01"`			(with `$field->format` = "Y-m";`)
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @param bool $getFormatedString Get value as formated string by `$this->format`.
	 * @return \DateTimeInterface|string|NULL
	 */
	public function GetMin ($getFormatedString = FALSE);

	/**
	 * Set minimum value for `Date`, `Time`, `DateTime`, `Week` 
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2017-01-01"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "14:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2017-01-01 14:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2017-W01"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2017-01"`			(with `$field->format` = "Y-m";`)
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @param \DateTimeInterface|string|int $min
	 * @return \MvcCore\Ext\Forms\IField
	 */
	public function & SetMin ($min);

	/**
	 * Get maximum value for `Date`, `Time`, `DateTime`, `Week` 
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2018-06-24"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "20:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2018-06-24 20:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2018-W25"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2018-06"`			(with `$field->format` = "Y-m";`)
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @param bool $getFormatedString Get value as formated string by `$this->format`.
	 * @return \DateTimeInterface|string|NULL
	 */
	public function GetMax ($getFormatedString = FALSE);

	/**
	 * Set maximum value for `Date`, `Time`, `DateTime`, `Week` 
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2018-06-24"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "20:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2018-06-24 20:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2018-W25"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2018-06"`			(with `$field->format` = "Y-m";`)
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @param \DateTimeInterface|string|int $max
	 * @return \MvcCore\Ext\Forms\IField
	 */
	public function & SetMax ($max);

	/**
	 * Get step value for `Date`, `Time`, `DateTime`, `Week` 
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @return int|NULL
	 */
	public function GetStep ();

	/**
	 * Set step value for `Date`, `Time`, `DateTime`, `Week` 
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @param int $step
	 * @return \MvcCore\Ext\Forms\IField
	 */
	public function & SetStep ($step);
}
