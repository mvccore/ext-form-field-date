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

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate submitted date format, min., max., step and 
 *				   remove dangerous characters.
 */
class		Date
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IMinMaxStepDates
{
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

	protected $format = NULL;

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
	 * Set up field instance, where is validated value by this 
	 * validator durring submit before every `Validate()` method call.
	 * This method is also called once, when validator instance is separately 
	 * added into already created field instance to process any field checking.
	 * @param \MvcCore\Ext\Forms\Field|\MvcCore\Ext\Forms\IField $field 
	 * @return \MvcCore\Ext\Forms\Validator|\MvcCore\Ext\Forms\IValidator
	 */
	public function & SetField (\MvcCore\Ext\Forms\IField & $field) {
		parent::SetField($field);
		
		if (!$field instanceof \MvcCore\Ext\Forms\Fields\IFormat)
			$this->throwNewInvalidArgumentException(
				"Field `".$field->GetName()."` doesn't implement interface `\\MvcCore\\Ext\\Forms\\Fields\\IFormat`."
			);
		
		if (!$field instanceof \MvcCore\Ext\Forms\Fields\IMinMaxStepDates)
			$this->throwNewInvalidArgumentException(
				"Field `".$field->GetName()."` doesn't implement interface `\\MvcCore\\Ext\\Forms\\Fields\\IMinMaxStepDates`."
			);

		if ($this->format !== NULL && $field->GetFormat() === NULL) {
			// if this validator is added into field as instance - check field if it has format attribute defined:
			$field->SetFormat($this->format);
		} else if ($this->format === NULL && $field->GetFormat() !== NULL) {
			// if validator is added as string - get format property from field:
			$this->format = $field->GetFormat();
		}
		if ($this->format === NULL) {
			$this->throwNewInvalidArgumentException(
				'No `format` property defined in current validator or in field.'	
			);
		}

		$fieldMin = $field->GetMin();
		if ($fieldMin !== NULL) {
			$this->min = $fieldMin;
		} else if ($this->min !== NULL && $fieldMin === NULL) {
			$field->SetMin($this->min);
		}
		$fieldMax = $field->GetMax();
		if ($fieldMax !== NULL) {
			$this->max = $fieldMax;
		} else if ($this->max !== NULL && $fieldMax === NULL) {
			$field->SetMax($this->max);
		}
		$fieldStep = $field->GetStep();
		if ($fieldStep !== NULL) {
			$this->step = $fieldStep;
		} else if ($this->step !== NULL && $fieldStep === NULL) {
			$field->SetStep($this->step);
		}

		return $this;
	}

	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		$safeValue = preg_replace('#[^a-zA-Z0-9\:\.\-\,/ ]#', '', $rawSubmittedValue);
		$date = @date_create_from_format($this->format, $safeValue);
		if ($date === FALSE || mb_strlen($safeValue) !== mb_strlen($rawSubmittedValue)) {
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

	protected function & checkMinMax (\DateTimeInterface & $date) {
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

	protected function & checkStep ($date) {
		if ($this->step !== NULL) {
			$fieldValue = $this->field->GetValue();
			if ($fieldValue instanceof \DateTimeInterface) {
				$fieldType = $this->field->GetType();
				$stepMatched = FALSE;
				if ($fieldType == 'month') {
					// months
					$interval = new \DateInterval('P' . $this->step . 'M');
				} else if ($fieldType == 'week') {
					// weeks
					$interval = new \DateInterval('P' . $this->step . 'W');
				} else if ($fieldType == 'time') {
					// seconds
					$interval = new \DateInterval('P' . $this->step . 'S');
				} else {
					// date, datetime, datetime-local - days
					$interval = new \DateInterval('P' . $this->step . 'D');
				}
				$formatedDate = $date->format($this->format);
				$datePeriod = new \DatePeriod($fieldValue, $interval, 2147483646);
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
				$datePeriod = new \DatePeriod($dateToCheckFrom, $interval, 2147483646);
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
