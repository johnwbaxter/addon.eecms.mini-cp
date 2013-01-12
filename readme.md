# Mini CP for ExpressionEngine

**A tiny little control panel**

Adds a toolbar at the top of your website in order to give you a quick entry search and easy access to your ExpressionEngine control panel sections.

**Search entries in a snap**

You can search through your entries accross all blog by using the integrated Ajax Search. When you click on one of the result entries, the module will automatically redirect you to the edit form in the control panel.

**Edit the current page**

It won't be a pain anymore to edit the page you're currently viewing on your website. Most clients don't know where they can edit the information they see. Place a simple tag in your templates and Mini CP will be able to detect that an entry is editable on the page, and will enable the "Edit Entry" button.

**Visual Notifications**

Never forget to validate a new comment again. You now have a special shortcut to the comments on your website. A number will be attached when pending comments are awaiting validation.

## Requirements

- ExpressionEngine 2
- jQuery 1.4+
- jQuery UI 1.8.3+

## Installation & Updating

1. Move the system/expressionengine/third_party/minicp/ folder to system/expressionengine/third_party/
1. Move the themes/third_party/minicp/ folder to themes/third_party/
1. Go to Add-Ons / Modules
1. Install the Mini CP module
1. Put this code in the tags : {exp:minicp jquery="yes" jqueryui="yes"}
1. Put this code, once, right after the tag : {exp:minicp:widget}


## Displaying the Mini CP to specific member groups

If you want to show Mini CP to specific groups, all you have to to is give the member group access to the control panel and allow the member group to use Mini CP :

1. Go to Members / Member Groups / Edit Group / Control Panel Access
1. Set to yes “Can access the Control Panel ?”
1. Set to yes “Can access Add-Ons section”
1. Set to yes “Can access Add-Ons Modules”
1. Go to Members / Member Groups / Edit Group / Modules Access Privileges
1. Set to yes “Can access module Mini CP”
1. Now this member group has access to the ExpressionEngine Control Panel, and to the Mini CP add-on.

The add-on is smart enough to show only the features that the member group has access to. For example, if the member group is not allowed to access the template section, then the “Template” item won’t show up.

Second example with the Mini CP Quick Search : it will only be displayed to member groups that are able to access channels and publish/edit entries.


## Tags & Parameters

In order to get Mini CP up and running on your ExpressionEngine website, you need place a header and a widget tag in your templates. Follow the instructions below to get started.

### Header Tag

Lets you call javascript and css files that are required by the add-on in order to load the widget. You can choose to load or not jQuery and jQuery UI, but make sure that you are only loading them once.

### Parameters

jquery=”yes”
jqueryui=”yes”

#### Example

	{exp:minicp jquery="yes" jqueryui="no"}
	
### Widget Tag

Loads the Mini CP widget on your website. It takes an optional entry_id parameter that will enable the “Edit” button when you need it. The Edit button takes the user straight to the entry he is viewing.

### Parameters

#### entry_id=”1”

{exp:channel:entries channel="news" limit="1"}
    {exp:minicp:widget entry_id="{entry_id}"}
{/exp:channel:entries} 


## Release Notes

**1.5** _(2011/06/28)_

- NSM Add-On Updater compatibility
- French translation files
- Updated links to control panel (base64 method deprecated by ExpressionEngine)
- Fixed a bug where module was not working when comment module not installed
- Fixed various CSS bugs

**1.4** _(2011/03/24)_

- Added Drag & Drop support for Mini CP items
- Improved CSS
- Improved IE compatibility

**1.3** _(2011/02/23)_

- Fixed a bug where links were broken for non MSM websites

**1.2** _(2011/02/22)_

- Fixed a bug where Module was not showing up in ExpressionEngine Add-Ons Modules list
- Fixed CSS bugs

**1.1** _(2011/02/21)_

- Added Multi Site Manager (MSM) support
- Fixed link related bugs

**1.0** _(2011/02/18)_

- Quick and easy access to your ExpressionEngine control panel from your website