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
trait TimeZone {

	/**
	 * Field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
	 * @see https://www.php.net/manual/en/timezones.php
	 * @var \DateTimeZone|NULL
	 */
	#protected $timeZone = NULL;
	
	/**
	 * Get field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
	 * @see https://www.php.net/manual/en/timezones.php
	 * @return \DateTimeZone|NULL
	 */
	public function GetTimeZone () {
		return $this->timeZone;
	}

	/**
	 * Set field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
	 * @see https://www.php.net/manual/en/timezones.php
	 * @param  \DateTimeZone|string|NULL $timeZone
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetTimeZone ($timeZone) {
		if (is_string($timeZone))
			$timeZone = new \DateTimeZone($timeZone);
		$this->timeZone = $timeZone;
		return $this;
	}
	
	/**
	 * Convert internal `\DateTimeInterface` object from server time zone 
	 * to user time zone for displaying or convert the object from user 
	 * submitted value to server object time zone.
	 * @param  \DateTimeInterface $value 
	 * @param  bool	              $fromUserInput 
	 * @return \DateTimeInterface
	 */
	public function ConvertTimeZone ($value, $fromUserInput = FALSE) {
		if ($this->timeZone === NULL) 
			return $value;
		$utcNow = new \DateTime('now', new \DateTimeZone('UTC'));
		$valueOffset = $this->timeZone->getOffset($utcNow);
		$userTimeZone = new \DateTimeZone(date_default_timezone_get());
		$userOffset = $userTimeZone->getOffset($utcNow);
		if ($userOffset === $valueOffset) {
			return $value;
		} else {
			$offset = $fromUserInput
				? $valueOffset - $userOffset
				: $userOffset - $valueOffset;
			$offsetAbs = abs($offset);
			$result = new \DateTime();
			$result->setTimezone($fromUserInput ? $this->timeZone : $userTimeZone);
			$result->setDate($value->format('Y'), $value->format('n'), $value->format('j'));
			$result->setTime($value->format('G'), $value->format('i'), $value->format('s'), $value->format('u'));
			$etaHours = intval(floor($offsetAbs / 3600));
			$etaMinutes = intval(floor(((floatval($offsetAbs) / 3600.0) - $etaHours) * 60.0));
			$etaSeconds = intval((floatval($offsetAbs / 60.0) - ($etaHours * 60) - $etaMinutes) * 60.0);
			$offsetInterval = new \DateInterval("PT{$etaHours}H{$etaMinutes}M{$etaSeconds}S");
			$offsetInterval->invert = $offset < 0 ? 1 : 0;
			return $result->add($offsetInterval);
		}
	}

	/**
	 * Get value time zone offset as an integer.
	 * @param  \DateTimeInterface $value 
	 * @param  bool	              $fromUserInput 
	 * @return int
	 */
	public function GetTimeZoneOffset ($value, $fromUserInput = FALSE) {
		if ($this->timeZone === NULL) 
			return 0;
		$utcNow = new \DateTime('now', new \DateTimeZone('UTC'));
		$valueOffset = $this->timeZone->getOffset($utcNow);
		$userTimeZone = new \DateTimeZone(date_default_timezone_get());
		$userOffset = $userTimeZone->getOffset($utcNow);
		if ($userOffset === $valueOffset) {
			return 0;
		} else {
			$offset = $fromUserInput
				? $valueOffset - $userOffset
				: $userOffset - $valueOffset;
			return $offset;
		}
	}
	
}
