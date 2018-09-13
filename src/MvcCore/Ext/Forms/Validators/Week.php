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
 * Responsibility: Validate submitted week format, min., max., step and 
 *				   remove dangerous characters.
 */
class Week extends \MvcCore\Ext\Forms\Validators\Date
{
	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_DATE_INVALID	=> "Field '{0}' requires a valid week format: '{1}'.",
		self::ERROR_DATE_TO_LOW		=> "Field '{0}' requires week higher or equal to '{1}'.",
		self::ERROR_DATE_TO_HIGH	=> "Field '{0}' requires week lower or equal to '{1}'.",
		self::ERROR_DATE_STEP		=> "Field '{0}' requires week in predefined weeks interval '{1}' from start point '{2}'.",
	];
}
