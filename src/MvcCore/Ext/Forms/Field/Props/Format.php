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
 * - `\MvcCore\Ext\Forms\Validators\Date`
 *    - `\MvcCore\Ext\Forms\Validators\DateTime`
 *    - `\MvcCore\Ext\Forms\Validators\Month`
 *    - `\MvcCore\Ext\Forms\Validators\Time`
 *    - `\MvcCore\Ext\Forms\Validators\Week`
 * @mixin \MvcCore\Ext\Forms\Field
 */
trait Format {

	/**
	 * String format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d" | "Y/m/d"`
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @var string
	 */
	#protected $format = NULL;
	
	/**
	 * Get string format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d" | "Y/m/d"`
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @return string
	 */
	public function GetFormat () {
		return $this->format;
	}

	/**
	 * Set string format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `$field->SetFormat("Y-m-d") | $field->SetFormat("Y/m/d");`
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @param  string $format
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetFormat ($format) {
		$this->format = $format;
		return $this;
	}
	
	/**
	 * Format `\DateTime` in for control rendering.
	 * @param  \DateTimeInterface|NULL $value 
	 * @return string
	 */
	public function Format ($value) {
		if ($value === NULL) return '';
		$userValue = $this->ConvertTimeZone($value, FALSE, static::$valueWithTime);
		return $userValue->format($this->format ?: static::$defaultFormat);
	}
	
	/**
	 * Create `\DateTimeInterface` value from given `\DateTimeInterface`
	 * or from given `int` (UNIX timestamp) or from `string` value
	 * (formatted by `date()` with `$this->format`) and return it.
	 * @see http://php.net/manual/en/class.datetime.php
	 * @param  \DateTimeInterface|int|string $inputValue
	 * @param  \DateTimeZone|NULL            $timeZone
	 * @param  bool                          $throwException Default `FALSE`.
	 * @throws \InvalidArgumentException
	 * @return \DateTimeInterface|NULL
	 */
	public function CreateFromInput ($inputValue, $timeZone = NULL, $throwException = FALSE) {
		$newValue = NULL;
		if ($inputValue instanceof \DateTime || $inputValue instanceof \DateTimeImmutable) {// PHP 5.4 compatible
			$newValue = $inputValue;
		} else if (is_int($inputValue)) {
			$newValue = new \DateTime();
			$newValue->setTimestamp($inputValue);
		} else if (is_string($inputValue)) {
			$format = $this->format ?: static::$defaultFormat;
			$format = '!' . ltrim($format, '!'); // to reset all other values not included in format into zeros
			$parsedValue = $timeZone === NULL
				? @\DateTime::createFromFormat($format, $inputValue)
				: \DateTime::createFromFormat($format, $inputValue, $timeZone);
			if ($parsedValue !== FALSE) {
				$newValue = $parsedValue;
			} else {
				if ($throwException) $this->throwNewInvalidArgumentException(
					"Value is not possible to parse into `\DateTimeInterface`:"
					." `{$inputValue}` by format: `{$format}`."
				);
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
