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
 *                 with types `date` and types `datetime-local`, `time`, 
 *                 `week` and `month` in extended classes. `Date` field and
 *                 it's extended fields have their own validator(s) to 
 *                 check submitted value format/min/max/step and dangerous 
 *                 characters in submitted date/time value(s).
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Date 
extends		\MvcCore\Ext\Forms\Field
implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
			\MvcCore\Ext\Forms\Fields\ILabel,
			\MvcCore\Ext\Forms\Fields\IFormat,
			\MvcCore\Ext\Forms\Fields\ITimeZone,
			\MvcCore\Ext\Forms\Fields\IMinMaxStepDates,
			\MvcCore\Ext\Forms\Fields\IDataList {

	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Label;
	use \MvcCore\Ext\Forms\Field\Props\Format;
	use \MvcCore\Ext\Forms\Field\Props\TimeZone;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepDates;
	use \MvcCore\Ext\Forms\Field\Props\DataList;
	use \MvcCore\Ext\Forms\Field\Props\Wrapper;
	
	/**
	 * MvcCore Extension - Form - Field - Date - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.3.0';

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
	 * `TRUE`if value could contains any time,
	 * for example hours, minutes, seconds or miliseconds.
	 * @var bool
	 */
	protected static $valueWithTime = FALSE;

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
	 * @var string|NULL
	 */
	protected $format = NULL;

	/**
	 * Field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
	 * @see https://www.php.net/manual/en/timezones.php
	 * @var \DateTimeZone|NULL
	 */
	protected $timeZone = NULL;

	/**
	 * Validators: 
	 * - `Date` - to check format, min., max., step and dangerous characters in submitted date value.
	 * @var \string[]|\Closure[]
	 */
	protected $validators = ['Date'];


	/**
	 * Create new form control instance based on `<input type="date" />`.
	 * 
	 * @param  array                         $cfg
	 * Config array with public properties and it's
	 * values which you want to configure, presented
	 * in camel case properties names syntax.
	 * 
	 * @param  string                        $name 
	 * Form field specific name, used to identify submitted value.
	 * This value is required for all form fields.
	 * @param  string                        $type 
	 * Fixed field order number, null by default.
	 * @param  int                           $fieldOrder
	 * Form field type, used in `<input type="...">` attribute value.
	 * Every typed field has it's own string value, but base field type 
	 * `\MvcCore\Ext\Forms\Field` has `NULL`.
	 * @param  \DateTimeInterface|string|int $value 
	 * Form field value. It could be string or array, int or float, it depends
	 * on field implementation. Default value is `NULL`.
	 * @param  string                        $title 
	 * Field title, global HTML attribute, optional.
	 * @param  string                        $translate 
	 * Boolean flag about field visible texts and error messages translation.
	 * This flag is automatically assigned from `$field->form->GetTranslate();` 
	 * flag in `$field->Init();` method.
	 * @param  string                        $translateTitle 
	 * Boolean to translate title text, `TRUE` by default.
	 * @param  array                         $cssClasses 
	 * Form field HTML element css classes strings.
	 * Default value is an empty array to not render HTML `class` attribute.
	 * @param  array                         $controlAttrs 
	 * Collection with field HTML element additional attributes by array keys/values.
	 * Do not use system attributes as: `id`, `name`, `value`, `readonly`, `disabled`, `class` ...
	 * Those attributes has it's own configurable properties by setter methods or by constructor config array.
	 * HTML field elements are meant: `<input>, <button>, <select>, <textarea> ...`. 
	 * Default value is an empty array to not render any additional attributes.
	 * @param  array                         $validators 
	 * List of predefined validator classes ending names or validator instances.
	 * Keys are validators ending names and values are validators ending names or instances.
	 * Validator class must exist in any validators namespace(s) configured by default:
	 * - `array('\MvcCore\Ext\Forms\Validators\');`
	 * Or it could exist in any other validators namespaces, configured by method(s):
	 * - `\MvcCore\Ext\Form::AddValidatorsNamespaces(...);`
	 * - `\MvcCore\Ext\Form::SetValidatorsNamespaces(...);`
	 * Every given validator class (ending name) or given validator instance has to 
	 * implement interface  `\MvcCore\Ext\Forms\IValidator` or it could be extended 
	 * from base  abstract validator class: `\MvcCore\Ext\Forms\Validator`.
	 * Every typed field has it's own predefined validators, but you can define any
	 * validator you want and replace them.
	 * 
	 * @param  string                        $accessKey
	 * The access key global attribute provides a hint for generating
	 * a keyboard shortcut for the current element. The attribute 
	 * value must consist of a single printable character (which 
	 * includes accented and other characters that can be generated 
	 * by the keyboard).
	 * @param  bool                          $autoFocus
	 * This Boolean attribute lets you specify that a form control should have input
	 * focus when the page loads. Only one form-associated element in a document can
	 * have this attribute specified. 
	 * @param  bool                          $disabled
	 * Form field attribute `disabled`, determination if field value will be 
	 * possible to change by user and if user will be graphically informed about it 
	 * by default browser behaviour or not. Default value is `FALSE`. 
	 * This flag is also used for sure for submit checking. But if any field is 
	 * marked as disabled, browsers always don't send any value under this field name
	 * in submit. If field is configured as disabled, no value sent under field name 
	 * from user will be accepted in submit process and value for this field will 
	 * be used by server side form initialization. 
	 * Disabled attribute has more power than required. If disabled is true and
	 * required is true and if there is no or invalid submitted value, there is no 
	 * required error and it's used value from server side assigned by 
	 * `$form->SetValues();` or from session.
	 * @param  bool                          $readOnly
	 * Form field attribute `readonly`, determination if field value will be 
	 * possible to read only or if value will be possible to change by user. 
	 * Default value is `FALSE`. This flag is also used for submit checking. 
	 * If any field is marked as read only, browsers always send value in submit.
	 * If field is configured as read only, no value sent under field name 
	 * from user will be accepted in submit process and value for this field 
	 * will be used by server side form initialization. 
	 * Readonly attribute has more power than required. If readonly is true and
	 * required is true and if there is invalid submitted value, there is no required 
	 * error and it's used value from server side assigned by 
	 * `$form->SetValues();` or from session.
	 * @param  bool                          $required
	 * Form field attribute `required`, determination
	 * if control will be required to complete any value by user.
	 * This flag is also used for submit checking. Default value is `NULL`
	 * to not require any field value. If form has configured it's property
	 * `$form->GetDefaultRequired()` to `TRUE` and this value is `NULL`, field
	 * will be automatically required by default form configuration.
	 * @param  int|string                    $tabIndex
	 * An integer attribute indicating if the element can take input focus (is focusable), 
	 * if it should participate to sequential keyboard navigation, and if so, at what 
	 * position. You can set `auto` string value to get next form tab-index value automatically. 
	 * Tab-index for every field in form is better to index from value `1` or automatically and 
	 * moved to specific higher value by place, where is form currently rendered by form 
	 * instance method `$form->SetBaseTabIndex()` to move tab-index for each field into 
	 * final values. Tab-index can takes several values:
	 * - a negative value means that the element should be focusable, but should not be 
	 *   reachable via sequential keyboard navigation;
	 * - 0 means that the element should be focusable and reachable via sequential 
	 *   keyboard navigation, but its relative order is defined by the platform convention;
	 * - a positive value means that the element should be focusable and reachable via 
	 *   sequential keyboard navigation; the order in which the elements are focused is 
	 *   the increasing value of the tab-index. If several elements share the same tab-index, 
	 *   their relative order follows their relative positions in the document.
	 * 
	 * @param  string                        $label
	 * Control label visible text. If field form has configured any translator, translation 
	 * will be processed automatically before rendering process. Default value is `NULL`.
	 * @param  bool                          $translateLabel
	 * Boolean to translate label text, `TRUE` by default.
	 * @param  string                        $labelSide
	 * Label side from rendered field - location where label will be rendered.
	 * By default `$this->labelSide` is configured to `left`.
	 * If you want to reconfigure it to different side,
	 * the only possible value is `right`.
	 * You can use constants:
	 * - `\MvcCore\Ext\Forms\IField::LABEL_SIDE_LEFT`
	 * - `\MvcCore\Ext\Forms\IField::LABEL_SIDE_RIGHT`
	 * @param  int                           $renderMode
	 * Rendering mode flag how to render field and it's label.
	 * Default value is `normal` to render label and field, label 
	 * first or field first by another property `$field->labelSide = 'left' | 'right';`.
	 * But if you want to render label around field or if you don't want
	 * to render any label, you can change this with constants (values):
	 * - `\MvcCore\Ext\Form::FIELD_RENDER_MODE_NORMAL`       - `<label /><input />`
	 * - `\MvcCore\Ext\Form::FIELD_RENDER_MODE_LABEL_AROUND` - `<label><input /></label>`
	 * - `\MvcCore\Ext\Form::FIELD_RENDER_MODE_NO_LABEL`     - `<input />`
	 * @param  array                         $labelAttrs
	 * Collection with `<label>` HTML element additional attributes by array keys/values.
	 * Do not use system attributes as: `id`,`for` or `class`, those attributes has it's own 
	 * configurable properties by setter methods or by constructor config array. Label `class` 
	 * attribute has always the same css classes as it's field automatically. 
	 * Default value is an empty array to not render any additional attributes.
	 * 
	 * @param  string                        $format
	 * Format mask to format given values in `Intl` extension `\DateTimeInterface` type
	 * or string format mask to format given values in `integer` type by PHP `date()` function.
	 * Example: `$field->SetFormat("Y-m-d") | $field->SetFormat("Y/m/d");`
	 * @param  \DateTimeZone|string			 $timeZone
	 * Field value time zone for internal `\DateTimeInterface` object.
	 * This is usually the same time zone as database time zone.
	 * This is not time zone for displaying, timezone for displaying is
	 * configured by global `date_default_timezone_set()` from user object.
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
	 * @param  int|float|string              $step
	 * Step value for `Date`, `Time`, `DateTime`, `Week`
	 * and `Month` fields, always in `integer`.
	 * For `Date` and `DateTime` fields, step is `int`, number of days.
	 * For `Time` fields, step is `int`, number of seconds.
	 * For `Week` and `Month` fields, step is `int`, number of weeks or months...
	 * 
	 * @param  string                        $list
	 * `DataList` form instance or `DataList` field unique name.
	 * 
	 * @param  string                        $wrapper
	 * Html code wrapper, wrapper has to contain replacement in string 
	 * form: `{control}`. Around this substring you can wrap any HTML 
	 * code you want. Default wrapper values is: `'{control}'`.
	 * 
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function __construct (
		array $cfg = [],

		$name = NULL,
		$type = NULL,
		$fieldOrder = NULL,
		$value = NULL,
		$title = NULL,
		$translate = NULL,
		$translateTitle = NULL,
		array $cssClasses = [],
		array $controlAttrs = [],
		array $validators = [],
		
		$accessKey = NULL,
		$autoFocus = NULL,
		$disabled = NULL,
		$readOnly = NULL,
		$required = NULL,
		$tabIndex = NULL,

		$label = NULL,
		$translateLabel = TRUE,
		$labelSide = NULL,
		$renderMode = NULL,
		array $labelAttrs = [],
		
		$format = NULL,
		$timeZone = NULL,
		$min = NULL,
		$max = NULL,
		$step = NULL,
		$list = NULL,
		$wrapper = NULL
	) {
		$this->consolidateCfg($cfg, func_get_args(), func_num_args());
		if (isset($cfg['min']))			$this->SetMin($cfg['min']);
		if (isset($cfg['max']))			$this->SetMax($cfg['max']);
		if (isset($cfg['step']))		$this->SetStep($cfg['step']);
		if (isset($cfg['timeZone']))	$this->SetTimezone($cfg['timeZone']);
		unset($cfg['min'], $cfg['max'], $cfg['step'], $cfg['timeZone']);
		parent::__construct($cfg);
		if ($this->list !== NULL)
			$this->SetList($this->list);
	}

	/**
	 * Get value as `\DateTimeInterface`.
	 * @see    http://php.net/manual/en/class.datetime.php
	 * @param  bool $getFormatedString Get value as formatted string by `$this->format`.
	 * @return \DateTimeInterface|string|NULL
	 */
	public function GetValue ($getFormatedString = FALSE) {
		return $getFormatedString
			? $this->Format($this->value)
			: $this->value;
	}
	
	/**
	 * Set value as `\DateTimeInterface` or `int` (UNIX timestamp) or 
	 * formatted `string` value by `date()` by `$this->format` 
	 * and use it internally as `\DateTimeInterface`.
	 * @see    http://php.net/manual/en/class.datetime.php
	 * @param  \DateTimeInterface|int|string $value
	 * @return \MvcCore\Ext\Forms\Fields\Date
	 */
	public function SetValue ($value) {
		/** @var \MvcCore\Ext\Forms\Fields\Date $this */
		$this->value = $this->CreateFromInput($value, $this->timeZone, TRUE);
		//$this->value = $this->RoundValue($value);
		return $this;
	}

	/**
	 * Round typed value into proper date/datetime value to be possible 
	 * to compare server and user input values correctly later in submit.
	 * @param  \DateTime|\DateTimeImmutable $value
	 * @return \DateTime|\DateTimeImmutable
	 */
	public function RoundValue ($value) {
		$rounded = clone $value;
		return $rounded->setTime(0, 0, 0, 0);
	}

	/**
	 * Return field specific data for validator.
	 * @param  array $fieldPropsDefaultValidValues 
	 * @return array
	 */
	public function & GetValidatorData ($fieldPropsDefaultValidValues = []) {
		$result = [
			'min'		=> $this->min, 
			'max'		=> $this->max, 
			'step'		=> $this->step,
			'format'	=> $this->format !== NULL 
				? $this->format 
				: static::$defaultFormat,
			'timeZone'	=> $this->timeZone
		];
		if ($this->list !== NULL) {
			$result['list'] = $this->list;
			$listField = $this->form->GetField($this->list);
			if ($listField instanceof \MvcCore\Ext\Forms\Fields\IOptions) 
				$result['options'] = $listField->GetOptions();
		}
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
		$listBefore = NULL;
		if ($this->list !== NULL) {
			$listBefore = $this->list;
			$this->list = $this->form->GetField($this->list)->GetId();
		}
		$attrsStrItems = [
			$this->RenderControlAttrsWithFieldVars([
				'list',
				'format'	=> 'data-format',
			])
		];
		$this->list = $listBefore;
		$dateProps = [
			'min'	=> $this->min,
			'max'	=> $this->max, 
			'step'	=> $this->step,
		];
		$view = $this->form->GetView() ?: $this->form->GetController()->GetView();
		if ($dateProps['min'] instanceof \DateTime || $dateProps['min'] instanceof \DateTimeImmutable) // PHP 5.4 compatible
			$dateProps['min'] = $this->Format($this->min);
		if ($dateProps['max'] instanceof \DateTime || $dateProps['max'] instanceof \DateTimeImmutable) // PHP 5.4 compatible
			$dateProps['max'] = $this->Format($this->max);
		foreach ($dateProps as $propName => $propValue)
			if ($propValue !== NULL)
				$attrsStrItems[] = $propName . '="' . $view->EscapeAttr($propValue) . '"';
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStrItems[] = 'form="' . $this->form->GetId() . '"';
		if ($this->value instanceof \DateTime || $this->value instanceof \DateTimeImmutable) // PHP 5.4 compatible
			$attrsStrItems[] = 'data-value="' . $this->value->format('c') . '"';
		if ($this->timeZone instanceof \DateTimeZone) {
			$attrsStrItems[] = 'data-timezone="' . $this->timeZone->getName() . '"';
			$attrsStrItems[] = 'data-offset="' . $this->GetTimeZoneOffset($this->value, FALSE) . '"';
		}
		$formViewClass = $this->form->GetViewClass();
		/** @var \stdClass $templates */
		$templates = static::$templates;
		$result = $formViewClass::Format($templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name,
			'type'		=> $this->type,
			'value'		=> $view->EscapeAttr(
				$this->value instanceof \DateTime || $this->value instanceof \DateTimeImmutable // PHP 5.4 compatible
					? $this->Format($this->value)
					: ($this->value ?: '')
			),
			'attrs'		=> count($attrsStrItems) > 0 ? ' ' . implode(' ', $attrsStrItems) : '',
		]);
		return $this->renderControlWrapper($result);
	}
}
