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
	 * @var int|NULL
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
			? $this->min->format($this->format)
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
		$this->min = $this->createDateTimeFromInput($min, TRUE);
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
			? $this->max->format($this->format)
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
		$this->max = $this->createDateTimeFromInput($max, TRUE);
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
	 * @return int|NULL
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
	 * @param  int $step
	 * @return \MvcCore\Ext\Forms\Fields\Date
	 */
	public function SetStep ($step) {
		$this->step = $step;
		return $this;
	}

	/**
	 * Create `\DateTimeInterface` value from given `\DateTimeInterface`
	 * or from given `int` (UNIX timestamp) or from `string` value
	 * (formatted by `date()` with `$this->format`) and return it.
	 * @see http://php.net/manual/en/class.datetime.php
	 * @param  \DateTimeInterface|int|string $inputValue
	 * @return \DateTimeInterface|NULL
	 */
	protected function createDateTimeFromInput ($inputValue, $throwException = FALSE) {
		$newValue = NULL;
		if ($inputValue instanceof \DateTimeInterface) {
			$newValue = $inputValue;
		} else if (is_int($inputValue)) {
			$newValue = new \DateTime();
			$newValue->setTimestamp($inputValue);
		} else if (is_string($inputValue)) {
			$format = $this->format !== NULL ? $this->format : static::$defaultFormat;
			$parsedValue = @date_create_from_format($format, $inputValue);
			if ($parsedValue === FALSE) {
				if ($throwException) $this->throwNewInvalidArgumentException(
					"Value is not possible to parse into `\DateTimeInterface`:"
					." `{$inputValue}` by format: `{$format}`."
				);
			} else {
				$newValue = $parsedValue;
			}
		} else if ($inputValue !== NULL && $throwException) {
			$this->throwNewInvalidArgumentException(
				"Value is not possible to convert into `\DateTimeInterface`:"
				." `{$inputValue}`. Value has to be formatted date string or UNIX"
				." epoch integer."
			);
		}
		return $newValue;
	}
}
