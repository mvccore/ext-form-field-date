# MvcCore - Extension - Form - Field - Date

[![Latest Stable Version](https://img.shields.io/badge/Stable-v4.3.1-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-form-field-date/releases)
[![License](https://img.shields.io/badge/Licence-BSD-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.3-brightgreen.svg?style=plastic)

MvcCore form extension with input field types date, datetime, time, week and month.

## Installation
```shell
composer require mvccore/ext-form-field-date
```

## Fields And Default Validators
- `input:date`
	- `Date`
		- **configured by default**
		- validate submitted value format, min., max., step and dangerous characters
- `input:datetime-local` (extended from `input:date`)
	- `DateTime`
		- **configured by default**
		- validate submitted value format, min., max., step and dangerous characters
- `input:time` (extended from `input:date`)
	- `Time`
		- **configured by default**
		- validate submitted value format, min., max., step and dangerous characters
- `input:week` (extended from `input:date`)
	- `Week`
		- **configured by default**
		- validate submitted value format, min., max., step and dangerous characters
- `input:month` (extended from `input:date`)
	- `Month`
		- **configured by default**
		- validate submitted value format, min., max., step and dangerous characters

## Features
- always server side checked attributes `required`, `disabled` and `readonly`
- all HTML5 specific and global atributes (by [Mozilla Development Network Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference))
- every field has it's build-in specific validator described above
- every build-in validator adds form error (when necessary) into session
  and than all errors are displayed/rendered and cleared from session on error page, 
  where user is redirected after submit
- any field is possible to render naturally or with custom template for specific field class/instance
- very extensible field classes - every field has public template methods:
	- `SetForm()`		- called immediatelly after field instance is added into form instance
	- `PreDispatch()`	- called immediatelly before any field instance rendering type
	- `Render()`		- called on every instance in form instance rendering process
		- submethods: `RenderNaturally()`, `RenderTemplate()`, `RenderControl()`, `RenderLabel()` ...
	- `Submit()`		- called on every instance when form is submitted

## Examples
- [**Example - CD Collection (mvccore/example-cdcol)**](https://github.com/mvccore/example-cdcol)
- [**Application - Questionnaires (mvccore/app-questionnaires)**](https://github.com/mvccore/app-questionnaires)

## Basic Example

```php
$form = (new \MvcCore\Ext\Form($controller))->SetId('demo');
...
$currentYear = intval(date("Y"));
$bornDate = new \MvcCore\Ext\Forms\Fields\Date();
$bornDate
	->SetName('born_date')
	->SetLabel('I was born:')
	//->SetFormat('Y-m-d') // not required, 'Y-m-d' by default
	->SetMin($currentYear - 130)
	->SetMax($currentYear);
$myMorningTime = new \MvcCore\Ext\Forms\Fields\Time([
	'name'		=> 'my_morning',
	'label'		=> 'I usually get up at morning at:',
	//'format'	=> 'H:i', // not required, 'H:i' by default
	'min'		=> '4:00',
	'max'		=> '10:00',
	'step'		=> 60 * 15, // 15 minutes
]);
...
$form->AddFields($bornDate, $myMorningTime);
```
