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

namespace MvcCore\Ext\Forms\Field\Props;

/**
 * Trait for classes:
 * - `\MvcCore\Ext\Forms\Fields\Date`
 *    - `\MvcCore\Ext\Forms\Fields\DateTime`
 *    - `\MvcCore\Ext\Forms\Fields\Month`
 *    - `\MvcCore\Ext\Forms\Fields\Time`
 *    - `\MvcCore\Ext\Forms\Fields\Week`
 * Trait contains properties, getters and setters for
 * protected properties `min`, `max` and `step`.
 * @mixin \MvcCore\Ext\Forms\Field
 */
trait MinMaxStepDates {

	/**
	 * Minimum value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2017-01-01"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "14:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2017-01-01 14:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2017-W01"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2017-01"`			(with `$field->format` = "Y-m";`)
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @var \DateTimeInterface|NULL
	 */
	protected $min = NULL;

	/**
	 * Maximum value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2018-06-24"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "20:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2018-06-24 20:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2018-W25"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2018-06"`			(with `$field->format` = "Y-m";`)
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @var \DateTimeInterface|NULL
	 */
	protected $max = NULL;

	/**
	 * Step value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @var int|string|NULL
	 */
	protected $step = NULL;

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
	 * @param  bool $getFormatedString Get value as formatted string by `$this->format`.
	 * @return \DateTimeInterface|string|NULL
	 */
	public function GetMin ($getFormatedString = FALSE) {
		return $getFormatedString
			? $this->Format($this->min, $this->format ?: static::$defaultFormat)
			: $this->min;
	}

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
	 * @param  \DateTimeInterface|string|int $min
	 * @return \MvcCore\Ext\Forms\Fields\Date
	 */
	public function SetMin ($min) {
		$this->min = (
			!($min instanceof \DateTimeInterface) && 
			$this instanceof \MvcCore\Ext\Forms\Fields\IFormat
		)
			? $this->CreateFromInput($min, $this->timeZone, TRUE)
			: $this->RoundValue($min);
		return $this;
	}

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
	 * @param  bool $getFormatedString Get value as formatted string by `$this->format`.
	 * @return \DateTimeInterface|string|NULL
	 */
	public function GetMax ($getFormatedString = FALSE) {
		return $getFormatedString
			? $this->Format($this->max, $this->format ?: static::$defaultFormat)
			: $this->max;
	}

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
	 * @param  \DateTimeInterface|string|int $max
	 * @return \MvcCore\Ext\Forms\Fields\Date
	 */
	public function SetMax ($max) {
		$this->max = (
			!($max instanceof \DateTimeInterface) && 
			$this instanceof \MvcCore\Ext\Forms\Fields\IFormat
		)
			? $this->CreateFromInput($max, $this->timeZone, TRUE)
			: $this->RoundValue($max);
		return $this;
	}

	/**
	 * Get step value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @return int|string|NULL
	 */
	public function GetStep () {
		return $this->step;
	}

	/**
	 * Set step value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * @see https://www.wufoo.com/html5/date-type/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @param  int|string $step
	 * @return \MvcCore\Ext\Forms\Fields\Date
	 */
	public function SetStep ($step) {
		$this->step = $step;
		return $this;
	}
	
}
