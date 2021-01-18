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
 * Responsibility: init, pre-dispatch and render `<input>` HTML element 
 *				   with types `date` and types `datetime-local`, `time`, 
 *				   `week` and `month` in extended classes. `Date` field and
 *				   it's extended fields have their own validator(s) to 
 *				   check submitted value format/min/max/step and dangerous 
 *				   characters in submitted date/time value(s).
 */
class		Date 
extends		\MvcCore\Ext\Forms\Field
implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
			\MvcCore\Ext\Forms\Fields\ILabel,
			\MvcCore\Ext\Forms\Fields\IMinMaxStepDates,
			\MvcCore\Ext\Forms\Fields\IFormat,
			\MvcCore\Ext\Forms\Fields\IDataList {

	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Label;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepDates;
	use \MvcCore\Ext\Forms\Field\Props\Format;
	use \MvcCore\Ext\Forms\Field\Props\DataList;
	use \MvcCore\Ext\Forms\Field\Props\Wrapper;
	
	/**
	 * MvcCore Extension - Form - Field - Date - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0';

	/**
	 * String format mask to format given values in `\DateTimeInterface` type for PHP `date_format()` function or 
	 * string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d"` for value like: `"2014-03-17"`.
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @var string
	 */
	protected static $defaultFormat = 'Y-m-d';

	/**
	 * Possible values: `date` and types `datetime-local`, 
	 * `time`, `week` and `month` in extended classes.
	 * @see http://www.html5tutorial.info/html5-date.php
	 * @var string
	 */
	protected $type = 'date';

	/**
	 * Value is used as `\DateTimeInterface`,
	 * but it could be set into field as formatted `string`
	 * by `$this->format` or as `int` (Unix epoch).
	 * @var \DateTimeInterface|NULL
	 */
	protected $value = NULL;
	
	/**
	 * String format mask to format given values in `\DateTimeInterface` type for PHP `date_format()` function or 
	 * string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `"Y-m-d"` for value like: `"2014-03-17"`.
	 * @see http://php.net/manual/en/datetime.createfromformat.php
	 * @see http://php.net/manual/en/function.date.php
	 * @var string
	 */
	protected $format = NULL;

	/**
	 * Validators: 
	 * - `Date` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Date'];

	/**
	 * Get value as `\DateTimeInterface`.
	 * @see http://php.net/manual/en/class.datetime.php
	 * @param bool $getFormatedString Get value as formatted string by `$this->format`.
	 * @return \DateTimeInterface|string|NULL
	 */
	public function GetValue ($getFormatedString = FALSE) {
		return $getFormatedString
			? $this->value->format($this->format)
			: $this->value;
	}
	
	/**
	 * Set value as `\DateTimeInterface` or `int` (UNIX timestamp) or 
	 * formatted `string` value by `date()` by `$this->format` 
	 * and use it internally as `\DateTimeInterface`.
	 * @see http://php.net/manual/en/class.datetime.php
	 * @param \DateTimeInterface|int|string $value
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetValue ($value) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		$this->value = $this->createDateTimeFromInput($value, TRUE);
		return $this;
	}

	/**
	 * Return field specific data for validator.
	 * @param array $fieldPropsDefaultValidValues 
	 * @return array
	 */
	public function & GetValidatorData ($fieldPropsDefaultValidValues = []) {
		$result = [
			'min'		=> $this->min, 
			'max'		=> $this->max, 
			'step'		=> $this->step,
			'format'	=> static::$defaultFormat,
		];
		return $result;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method event if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Set up tab-index if necessary.
	 * @return void
	 */
	public function PreDispatch () {
		parent::PreDispatch();
		$this->preDispatchTabIndex();
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Forms\Field\Rendering` 
	 * in rendering process. Do not use this method even if you don't develop any form field.
	 * 
	 * Render control tag only without label or specific errors.
	 * @return string
	 */
	public function RenderControl () {
		$fieldVarsToAttrs = [
			'list',
			'format'	=> 'data-format',
		];
		$attrsStr = $this->renderControlAttrsWithFieldVars($fieldVarsToAttrs);
		$dateProps = [
			'min'	=> $this->min,
			'max'	=> $this->max, 
			'step'	=> $this->step,
		];
		if ($dateProps['min'] instanceof \DateTimeInterface) 
			$dateProps['min'] = $this->min->format(static::$defaultFormat);
		if ($dateProps['max'] instanceof \DateTimeInterface) 
			$dateProps['max'] = $this->max->format(static::$defaultFormat);
		$attrsStrSep = strlen($attrsStr) > 0 ? ' ' : '';
		foreach ($dateProps as $propName => $propValue) {
			if ($propValue !== NULL) {
				$attrsStr .= $attrsStrSep . $propName . '="' . $propValue . '"';
				$attrsStrSep = ' ';
			}
		}
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= $attrsStrSep . 'form="' . $this->form->GetId() . '"';
		if ($this->format !== NULL) {
			$valueByDefinedFormat = htmlspecialchars_decode(htmlspecialchars(
				($this->value instanceof \DateTimeInterface 
					? $this->value->format($this->format)
					: $this->value), 
				ENT_QUOTES), ENT_QUOTES
			);
			$attrsStr .= $attrsStrSep . 'data-value="' . $valueByDefinedFormat . '"';
		}
		$formViewClass = $this->form->GetViewClass();
		/** @var $templates \stdClass */
		$templates = static::$templates;
		$result = $formViewClass::Format($templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name,
			'type'		=> $this->type,
			'value'		=> htmlspecialchars_decode(htmlspecialchars(
				($this->value instanceof \DateTimeInterface 
					? $this->value->format(static::$defaultFormat)
					: $this->value), 
				ENT_QUOTES), ENT_QUOTES
			),
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
		return $this->renderControlWrapper($result);
	}
}
