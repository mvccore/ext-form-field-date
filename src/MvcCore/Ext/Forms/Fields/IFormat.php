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
interface IFormat {

	/**
	 * Get string format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d" | "Y/m/d"`
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @return string
	 */
	public function GetFormat ();

	/**
	 * Set string format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `$field->SetFormat("Y-m-d") | $field->SetFormat("Y/m/d");`
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @param  string $format
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetFormat ($format);

	/**
	 * Format `\DateTime` in for control rendering.
	 * @param  \DateTimeInterface|NULL $value 
	 * @return string
	 */
	public function Format ($value);

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
	public function CreateFromInput ($inputValue, $timeZone = NULL, $throwException = FALSE);
}
