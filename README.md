## API Documentation

Check out [Audit log API Documentation](http://imsamurai.github.io/CakePHP-Audit-Log-Plugin/docs/master/)

## Abstract

[![Build Status](https://travis-ci.org/imsamurai/CakePHP-Audit-Log-Plugin.png)](https://travis-ci.org/imsamurai/CakePHP-Audit-Log-Plugin) [![Coverage Status](https://coveralls.io/repos/imsamurai/CakePHP-Audit-Log-Plugin/badge.png?branch=master)](https://coveralls.io/r/imsamurai/CakePHP-Audit-Log-Plugin?branch=master) [![Latest Stable Version](https://poser.pugx.org/imsamurai/audit-log/v/stable.png)](https://packagist.org/packages/imsamurai/audit-log) [![Total Downloads](https://poser.pugx.org/imsamurai/audit-log/downloads.png)](https://packagist.org/packages/imsamurai/audit-log) [![Latest Unstable Version](https://poser.pugx.org/imsamurai/audit-log/v/unstable.png)](https://packagist.org/packages/imsamurai/audit-log) [![License](https://poser.pugx.org/imsamurai/audit-log/license.png)](https://packagist.org/packages/imsamurai/audit-log)

A logging plugin for [CakePHP](http://cakephp.org). The included `AuditableBehavior`  creates an audit history for each instance of a model to which it's attached.

The behavior tracks changes on two levels. It takes a snapshot of the fully hydrated object _after_ a change is complete and it also records each individual change in the case of an update action.

## Features

* Support for CakePHP 2.0. Thanks, @jasonsnider.
* Tracks object snapshots as well as individual property changes.
* Allows each revision record to be attached to a source -- usually a user -- of responsibility for the change.
* Allows developers to ignore changes to specified properties. Properties named `created`, `updated` and `modified` are ignored by default, but these values can be overwritten.
* Handles changes to HABTM associations.
* Fully compatible with the [`PolymorphicBehavior`](http://bakery.cakephp.org/articles/view/polymorphic-behavior).
* Does not require or rely on the existence of explicit models revisions (`AuditLog`) and deltas (`AuditLogDeltas`).

## Installation

### CakePHP >= 2.0

#### As an Archive  

1. Download and extract the source to `app/Plugin/AuditLog`.

#### As a Submodule

1. `$ git submodule add git://github.com/imsamurai/CakePHP-Audit-Log-Plugin.git <path_to>/app/Plugin/AuditLog`
1. `$ git submodule init`
1. `$ git submodule update`

#### Via composer

1. add into your composer 
	"require": {
		"imsamurai/audit-log": "1.1.*",
	}
1. `composer update`

To create tables you can use schema shell. To create tables execute:

    cd <path_to>/app/
    chmod +x ./Console/cake
    ./Console/cake schema create -p AuditLog

### CakePHP 1.3.x

For use with CakePHP 1.3.x, be sure to use code from the `1.3` branch and follow the instructions in that README file (NOT MAINTAINED).

### Next Steps

1. Run the `install.sql` file on your CakePHP application database or use schema. This will create the `audits` and `audit_deltas` tables that will store each object's relevant change history.
1. Add plugin into `bootstrap.php`

	```php
	CakePlugin::load('AuditLog', array('bootstrap' => true));
	```

1. Create a `currentUser()` method, if desired.

    The `AuditableBehavior` optionally allows each changeset to be "owned" by a "source" -- typically the user responsible for the change. Since user and authentication models vary widely, the behavior supports a callback method that should return the value to be stored as the source of the change, if any.

    The `currentUser()` method must be available to every model that cares to track a source of changes, so I recommend that a copy of CakePHP's `app_model.php` file be created and the method added there. Keep it DRY, right?

    The behavior expects the `currentUser()` method to return an associative array with an `id` key. Continuing from the example above, the following code might appear in the `AppModel`:
	```php
	public function currentUser() {
	  return AuthComponent::user();
	}
	```
1. Attach the behavior to any desired model and configure.

## Usage

### AuditableBehavior

Applying the `AuditableBehavior` to a model is essentially the same as applying any other CakePHP behavior. The behavior does offer a few configuration options:

<dl>
	<dt>`ignore`</dt>
	<dd>An array of property names to be ignored when records are created in the deltas table.</dd>
	<dt>`habtm`</dt>
	<dd>An array of models that have a HABTM relationship with the acting model and whose changes should be monitored with the model. If the HABTM model is auditable in its own right, don't include it here. This option is for related models whose changes are _only_ tracked relative to the acting model.</dd>
</dl>

### AuditHelper

- `user(user)` render user name with link to user profile (see config `AuditLog.User`)
- `listBlock(conditions, ajax)` render widget with compact table of audit logs depends on `conditions` and `ajax`. Second parameter means that widget will be loaded by ajax or request action (default is ajax). This method simply invoke action `AuditController::index` with `list=1` and `conditions` (also `count=<count>` if it set).

### AuditController

For filter by date you can use all formats (single or range) that constructor of `DateTime` can understand. For example:

- `2014/11/05 TO 2014/11/06` - will be range from 05.11.2014 00:00:00 to 06.11.2014 00:00:00
- `2014/11/05 01:02:20 TO 2014/11/06 15:02:21` - from 05.11.2014 01:02:20 to 06.11.2014 15:02:21
- `05.11.2014 01:02:20 to 06.11.2014 15:02:21` - from 05.11.2014 01:02:20 to 06.11.2014 15:02:21
- `2014/11/05` - from 05.11.2014 00:00:00 to 05.11.2014 23:59:59
- `2014/11/05 03:40:21` - date must be equals to 05.11.2014 03:40:21

Hint: you can use [bootstrap daterange picker](https://github.com/dangrossman/bootstrap-daterangepicker) for this field.

### Syntax

```php
# Simple syntax accepting default options
class Task extends AppModel {
	public $actsAs = array( 'AuditLog.Auditable' );
	  
	# 
	# Additional model code.
	#
}

# Syntax with explicit options
class Task extends AppModel {
	public $actsAs = array(
		'AuditLog.Auditable' => array(
			'ignore' => array( 'active', 'name', 'updated' ),
			'habtm'  => array( 'Type', 'Project' )
		)
	);
	
	# 
	# Additional model code.
	#
}
```

## Limitations

* The master branch is not backwards compatible with CakePHP <=1.3.x. If you need compatibility with these version please install the code from the `1.3` branch and follow the instructions in that README. 

## License

This code is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).

## Notes

Feel free to submit bug reports or suggest improvements in a ticket or fork this project and improve upon it yourself. Contributions welcome.

## Changelog

### 1.1.1
- Add link `view all` in widget

### 1.1.0
- Add to composer and travis. 
- Change source_id to user_id field in db. 
- Handle case of saving AuditDelta for array value in field (actual saving for model is provided by https://github.com/imsamurai/cakephp-serializable-behaviour)
- Default view for list of audit records with pagination and filter (for nice style you need bootstrap 2)
- Default view for concrete audit record and it deltas
- Helper that has ajax/requestAction widget for display short list of audit records and format user email with link to user view in your application
- Some additional parameters you can see in plugin bootstrap.php
