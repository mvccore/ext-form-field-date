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

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, predispatch and render `<input>` HTML element 
 *				   with types `date` and types `datetime-local`, `time`, 
 *				   `week` and `month` in extended classes. `Date` field and
 *				   it's extended fields have their own validator(s) to 
 *				   check submitted value format/min/max/step and dangerous 
 *				   characters in submitted date/time value(s).
 */
class Date 
	extends		\MvcCore\Ext\Forms\Field
	implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
				\MvcCore\Ext\Forms\Fields\ILabel,
				\MvcCore\Ext\Forms\Fields\IMinMaxStepDates,
				\MvcCore\Ext\Forms\Fields\IFormat,
				\MvcCore\Ext\Forms\Fields\IDataList
{
	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Label;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepDates;
	use \MvcCore\Ext\Forms\Field\Props\Format;
	use \MvcCore\Ext\Forms\Field\Props\DataList;
	use \MvcCore\Ext\Forms\Field\Props\Wrapper;

	/**
	 * Possible values: `date` and types `datetime-local`, 
	 * `time`, `week` and `month` in extended classes.
	 * @see http://www.html5tutorial.info/html5-date.php
	 * @var string
	 */
	protected $type = 'date';

	/**
	 * Value is used as `\DateTimeInterface`,
	 * but it could be set into field as formated `string`
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
	protected $format = 'Y-m-d';

	/**
	 * Validators: 
	 * - `Date` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Date'];

	/**
	 * Get value as `\DateTimeInterface`.
	 * @see http://php.net/manual/en/class.datetime.php
	 * @param bool $getFormatedString Get value as formated string by `$this->format`.
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
	 * @return \MvcCore\Ext\Forms\Field|\MvcCore\Ext\Forms\IField
	 */
	public function & SetValue ($value) {
		$this->value = $this->createDateTimeFromInput($value, TRUE);
		return $this;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method event if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Set up tabindex if necessary.
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
		$attrsStr = $this->renderControlAttrsWithFieldVars([
			'list',
		]);
		$dateProps = [
			'min'	=> $this->min,
			'max'	=> $this->max, 
			'step'	=> $this->step,
		];
		if ($dateProps['min'] instanceof \DateTimeInterface) 
			$dateProps['min'] = $this->min->format($this->format);
		if ($dateProps['max'] instanceof \DateTimeInterface) 
			$dateProps['max'] = $this->max->format($this->format);
		$attrsStrSep = strlen($attrsStr) > 0 ? ' ' : '';
		foreach ($dateProps as $propName => $propValue) {
			if ($propValue !== NULL) {
				$attrsStr .= $attrsStrSep . $propName . '="' . $propValue . '"';
				$attrsStrSep = ' ';
			}
		}
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= $attrsStrSep . 'form="' . $this->form->GetId() . '"';
		$formViewClass = $this->form->GetViewClass();
		$result = $formViewClass::Format(static::$templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name,
			'type'		=> $this->type,
			'value'		=> htmlspecialchars(
				($this->value instanceof \DateTimeInterface 
					? $this->value->format($this->format)
					: $this->value), 
				ENT_QUOTES
			),
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
		return $this->renderControlWrapper($result);
	}
}
