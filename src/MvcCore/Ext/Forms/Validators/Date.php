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

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate submitted date format, min., max., step and
 *                 remove dangerous characters.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Date
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IMinMaxStepDates {

	use \MvcCore\Ext\Forms\Field\Props\Format;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepDates;

	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_DATE_INVALID	= 0;
	const ERROR_DATE_TO_LOW		= 1;
	const ERROR_DATE_TO_HIGH	= 2;
	const ERROR_DATE_STEP		= 3;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_DATE_INVALID	=> "Field '{0}' requires a valid date format: '{1}'.",
		self::ERROR_DATE_TO_LOW		=> "Field '{0}' requires date higher or equal to '{1}'.",
		self::ERROR_DATE_TO_HIGH	=> "Field '{0}' requires date lower or equal to '{1}'.",
		self::ERROR_DATE_STEP		=> "Field '{0}' requires date in predefined days interval '{1}' from start point '{2}'.",
	];

	/**
	 * Field specific values (camel case) and their validator default values.
	 * @var array
	 */
	protected static $fieldSpecificProperties = [
		'min'		=> NULL,
		'max'		=> NULL,
		'step'		=> NULL,
		'format'	=> NULL,
	];

	/**
	 * Error messages replacements. How to get more human form shortcuts from
	 * PHP `date()` special chars to more human shortcuts.
	 * @var array
	 */
	protected static $errorMessagesFormatReplacements = [
		'd' => 'DD',
		'j' => 'D',
		'D' => 'Mon-Sun',
		'l' => 'Monday-Sunday',
		'm' => 'MM',
		'n' => 'M',
		'M' => 'Jan-Dec',
		'F' => 'January-December',
		'Y' => 'YYYY',
		'y' => 'YY',
		'a' => 'am/pm',
		'A' => 'AM/PM',
		'g' => '1-12',
		'h' => '01-12',
		'G' => '01-12',
		'H' => '00-23',
		'i' => '00-59',
		's' => '00-59',
		'u' => '0-999999',
	];
	
	/**
	 * String format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d" | "Y/m/d"`
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @var string
	 */
	protected $format = NULL;


	/**
	 * Create date validator instance.
	 * 
	 * @param  array                         $cfg
	 * Config array with protected properties and it's 
	 * values which you want to configure, presented 
	 * in camel case properties names syntax.
	 *
	 * @param  string                        $format
	 * Format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `$field->SetFormat("Y-m-d") | $field->SetFormat("Y/m/d");`
	 * 
	 * @param  \DateTimeInterface|string|int $min
	 * Minimum value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2017-01-01"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "14:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2017-01-01 14:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2017-W01"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2017-01"`			(with `$field->format` = "Y-m";`)
	 * @param  \DateTimeInterface|string|int $max
	 * Maximum value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` field(s) in `string` value.
	 * Example string values for date and time fields:
	 * - `Date		=> "2018-06-24"`		(with `$field->format` = "Y-m-d";`)
	 * - `Time		=> "20:00"`				(with `$field->format` = "H:i";`)
	 * - `DateTime	=> "2018-06-24 20:00"`	(with `$field->format` = "Y-m-d H:i";`)
	 * - `Week		=> "2018-W25"`			(with `$field->format` = "o-\WW";`)
	 * - `Month		=> "2018-06"`			(with `$field->format` = "Y-m";`)
	 * @param  int|float                     $step
	 * Step value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * 
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function __construct(
		array $cfg = [],
		$format = NULL,
		$min = NULL,
		$max = NULL,
		$step = NULL
	) {
		$this->consolidateCfg($cfg, func_get_args(), func_num_args());
		parent::__construct($cfg);
	}
	
	/**
	 * Set error messages replacements. How to get more human form shortcuts from
	 * PHP `date()` special chars to more human shortcuts.
	 * @param  array $errorMessagesFormatReplacements 
	 * @return array
	 */
	public static function SetErrorMessagesFormatReplacements (array $errorMessagesFormatReplacements) {
		return static::$errorMessagesFormatReplacements = $errorMessagesFormatReplacements;
	}

	/**
	 * Get error messages replacements. How to get more human form shortcuts from
	 * PHP `date()` special chars to more human shortcuts.
	 * @return array
	 */
	public static function GetErrorMessagesFormatReplacements () {
		return static::$errorMessagesFormatReplacements;
	} 
	
	/**
	 * Set up field instance, where is validated value by this
	 * validator during submit before every `Validate()` method call.
	 * This method is also called once, when validator instance is separately
	 * added into already created field instance to process any field checking.
	 * @param  \MvcCore\Ext\Forms\Field $field
	 * @return \MvcCore\Ext\Forms\Validator
	 */
	public function SetField (\MvcCore\Ext\Forms\IField $field) {
		parent::SetField($field);
		if ($this->format === NULL) {
			$this->throwNewInvalidArgumentException(
				'No `format` property defined in current validator or in field.'
			);
		}
		return $this;
	}

	/**
	 * Validate submitted date format, min., max., step and remove dangerous characters.
	 * @param  string|array      $rawSubmittedValue Raw user input.
	 * @return string|array|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		$safeValue = preg_replace('#[^a-zA-Z0-9\:\.\-\,/ ]#', '', $rawSubmittedValue);
		$safeValueLength = mb_strlen($safeValue);
		if ($safeValueLength === 0) return NULL;
		$date = @date_create_from_format($this->format, $safeValue);
		if ($date === FALSE || $safeValueLength !== mb_strlen($rawSubmittedValue)) {
			$this->field->AddValidationError(
				static::GetErrorMessage(static::ERROR_DATE_INVALID),
				[strtr($this->format, static::$errorMessagesFormatReplacements)]
			);
			$date = NULL;
		} else {
			$date = $this->checkMinMax($date);
			$date = $this->checkStep($date);
		}
		return $date;
	}

	/**
	 * Validate submitted date min. and max. if necessary.
	 * @param  \DateTimeInterface $date
	 * @return \DateTimeInterface
	 */
	protected function checkMinMax ($date) {
		if ($this->min !== NULL && $date < $this->min) {
			$this->field->AddValidationError(
				static::GetErrorMessage(static::ERROR_DATE_TO_LOW),
				[$this->min->format($this->format)]
			);
		}
		if ($this->max !== NULL && $date > $this->max) {
			$this->field->AddValidationError(
				static::GetErrorMessage(static::ERROR_DATE_TO_HIGH),
				[$this->max->format($this->format)]
			);
		}
		return $date;
	}

	/**
	 * Validate submitted date step if necessary.
	 * @param  \DateTimeInterface $date
	 * @return \DateTimeInterface
	 */
	protected function checkStep ($date) {
		if ($this->step !== NULL) {
			$fieldValue = $this->field->GetValue();
			if ($fieldValue instanceof \DateTime || $fieldValue instanceof \DateTimeImmutable) { // PHP 5.4 compatible
				$fieldType = $this->field->GetType();
				$stepMatched = FALSE;
				static $dateIntervalSpecs = [
					'month'				=> 'M', // months
					'week'				=> 'W', // weeks
					'time'				=> 'S', // seconds
					'date'				=> 'D', // days
					'datetime'			=> 'D', // days
					'datetime-local'	=> 'D', // days
				];
				$interval = new \DateInterval('P' . $this->step . $dateIntervalSpecs[$fieldType]);
				$formatedDate = $date->format($this->format);
				$datePeriod = new \DatePeriod($fieldValue, $interval, PHP_INT_MAX);
				$previousValue = $fieldValue;
				$dateToCheckFrom = $fieldValue;
				foreach ($datePeriod as $datePoint) {
					if ($datePoint > $date) {
						$dateToCheckFrom = $previousValue;
						break;
					} else {
						$previousValue = $datePoint;
					}
				}
				$datePeriod = new \DatePeriod($dateToCheckFrom, $interval, PHP_INT_MAX);
				$counter = 0;
				foreach ($datePeriod as $datePoint) {
					if ($counter > 3) break;
					$formatedDatePoint = $datePoint->format($this->format);
					if ($formatedDate === $formatedDatePoint) {
						$stepMatched = TRUE;
						break;
					} else {
						$counter++;
					}
				}
				if (!$stepMatched) {
					$this->field->AddValidationError(
						static::GetErrorMessage(static::ERROR_DATE_STEP),
						[$this->step, $fieldValue->format($this->format)]
					);
					$date = $fieldValue;
				}
			}
		}
		return $date;
	}
}
