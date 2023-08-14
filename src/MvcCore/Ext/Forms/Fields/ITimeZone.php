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
 * Responsibility: define getters and setters for field property `format`.
 * Interface for classes:
 * - `\MvcCore\Ext\Forms\Fields\Date`
 *    - `\MvcCore\Ext\Forms\Fields\DateTime`
 *    - `\MvcCore\Ext\Forms\Fields\Month`
 *    - `\MvcCore\Ext\Forms\Fields\Time`
 *    - `\MvcCore\Ext\Forms\Fields\Week`
 * - `\MvcCore\Ext\Forms\Validators\Date`
 */
interface ITimeZone {

	/**
	 * Get field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
	 * @see https://www.php.net/manual/en/timezones.php
	 * @return \DateTimeZone|NULL
	 */
	public function GetTimeZone ();

	/**
	 * Set field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
	 * @see https://www.php.net/manual/en/timezones.php
	 * @param  \DateTimeZone|string|NULL $timeZone
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetTimeZone ($timeZone);
	
	/**
	 * Convert internal `\DateTimeInterface` object from server time zone 
	 * to user time zone for displaying or convert the object from user 
	 * submitted value to server object time zone.
	 * @param  \DateTimeInterface $value 
	 * @param  bool	              $fromUserInput 
	 * @param  bool               $moveTimeByZone
	 * @return \DateTimeInterface
	 */
	public function ConvertTimeZone ($value, $fromUserInput = FALSE, $moveTimeByZone = TRUE);

	/**
	 * Get value time zone offset as an integer.
	 * @param  \DateTimeInterface $value 
	 * @param  bool	              $fromUserInput 
	 * @return int
	 */
	public function GetTimeZoneOffset ($value, $fromUserInput = FALSE);
}
