<?php
namespace Yuma;
//	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
$langArray = Array(
//	'_raw_server_string__NEVER_CHANGE' => 'translation for this language__CAN BE CHANGED',
	/* general */
	'_details' => 'details',
	'_Home' => 'Home',
	'_migrate_desc' => 'Open an existing database without forms AND create forms',	// poage desc
	'_settings_desc' => '',
	'_Select' => '-Select-',
	'_default' => 'default',
	'_First' => 'First',
	'_none' => ' ',
	'_none!' => 'none',
	'_empty' => 'empty',
	'_inherit' => 'inherit',
	'_optional' => '(optional)',
	'_units' => 'units',
	'_advanced' => 'advanced',
	'_top' => 'top of page',
	'_unchanged' => 'unchanged',
	'_YES' => 'YES',
	'_NO' => 'NO',
	'_loading...' => 'loading...',
	'_readonly' => 'readonly',
	'_Query %s' => "Query: '%s'",
	//'_Not visible' => 'Not visible',
	'_List all' => 'List all',
	'_Select All' => 'Select All',
	'_Must select' => "- Must select -",
	'_Save changes' => "Save changes",
	'_move handle' => "drag handle",
	'_Only visible to author/ editor' => 'Only visible to author/ editor',
	//'_Are you sure' => 'Are you sure',
	'_was %s' => "current: '%s'",
	'_Modify %s' => "Modify '%s'",
	'_Modified %s' => "Modified '%s'",
	'_Goto %s' => "Goto '%s'",
	'_Created %s' => "Created '%s'",
	//'_Dropped %s' => "Dropped '%s'",
	'_Deleted %s' => "Deleted '%s'",
	'_multiselectBtnPh' => 'Hold Shift or Ctrl to select multiple',
	'_nojs_banner' => 'JavaScript is not functional - user interaction will be comprimised.',
	'_no specific value' => 'no specific value',
	'_Really reset all form values to their defaults' => 'Really reset all form values to their defaults',
	'_Ensure the name is unique and not a reserved word' => 'Ensure the name is unique and not a reserved word',

	/* home */
	'_New' => 'Newish',	// nav items
	'_Open...' => 'Open...',
	'_Migrate...' => 'Migrate...',
	'_Remove...' => 'Remove...',
	'_Settings' => 'Settings',
	'_Welcome to BuildForm' => 'Welcome to BuildForm',	// title
	'_Recents' => 'Recents',	// pane2 table caption

	/* note */
	'_OK' => 'OK',	// buttons
	'_Proceed' => 'Proceed',
	'_Ignore' => 'Ignore',
	'_Cancel' => 'Cancel',
	'_Continue' => 'Continue',

	/* general errors/notices */
	'_Do you really want to do this?' => 'Do you really want to do this?',
	'_Cannot find %s' => "Cannot find '%s'",	// error
	//'_Created %s. Press OK to continue.' => "Created '%s'. Press OK to continue.",	// proceed
	'_loaded %s' => "loaded: %s",
	'_Database error: %s' => 'Database error: %s',
	'_SQL error: %s' => "SQL error: %s",
	'_DB_TYPE was inccorectly set as %s.' => "DB_TYPE was inccorectly set as %s.",
	'_Dropped %s. Press OK to continue.' => "Dropped '%s'. Press OK to continue.",	// proceed
	'_Cannot get information for %s' => "Cannot get information for '%s'",
	'_Ensure the name is unique and not a reserved word' => 'Ensure the name is unique and not a reserved word',

	/* database */
	'_dbs_desc' => 'Create, delete or list databases',	// page desc
	'_db_desc' => 'Create, delete, rename or list tables or forms within a database',
	'_tbl_desc' => 'Create, delete or list records within a database table',	// page desc
	'_frm_desc' => 'Create, delete, view or edit a database form',	// page desc
	'_cols_desc' => 'Create, delete, edit or list columns within a database table',	// page desc
	'_col_desc' => 'Create a database table column',	// page desc
	'_Databases' => 'Databases',
	'_List databases' => 'List databases',
	'_Database name' => 'Database name',	// input label
	'_Add database' => 'Add database',	// submit button
	'_No databases' => "No databases",	// empty
	'_No database selected' => 'No database selected',	// error
	'_Select a database' => 'Select a database:',	// h3
	'_Cannot list databases' => 'Cannot list databases',
	'_Add a new database' => 'Add a new database',
	'_Database %s' => "Database <span class='highlight'>%s</span>",	// a title
	'_Database %s!' => "Database '%s'",	// a title
	'_Rename %s' => "Rename '%s'",
	'_Renamed %s' => "Renamed '%s'",
	'_Cannot create database %s' => "Cannot create database '%s'",	// error
	'_Not a forms database %s' => "Not a forms database '%s'",
	'_Cannot remove database %s' => "Cannot remove database '%s'",
	'_Really remove database %s' => "Really remove database `%s`?",
	//'_Really remove database %s' => "Really remove database <span class='highlight'>%s</span>?",
	'_Cannot select database %s' => "Cannot select database '%s'",
	'_Remove database %s' => "Remove database '%s'",
	'_Cannot drop database %s' => "Cannot drop database '%s'",	//error
	'_Cannot remove non-empty database %s' => "Cannot remove non-empty database '%s'",
	'_Cannot get information for non-Forms database %s' => "Cannot get information for non-Forms database '%s'",
	
	/* use */
	//'_OR' => 'OR',
	//'_Choose forms to load' => 'Choose forms to load:',
	//'_Forms name' => 'Forms name',	// label
	//'_Load forms' => 'Load forms',	// button
	//"_Database %s" => "Database '%s'",	// a title
	//"_Table %s" => "Table '%s'",	// a title
	
	/* table/form */
	'_table_desc' => 'Database tables',	// desc
	'_Tables' => "Tables",	// heading
//	'_List tables' => 'List tables',
	'_Forms' => "Forms",	// heading
	'_No tables' => "No tables",	// empty
	'_No forms' => "No forms",	// empty
	'_Table name' => 'Table name',	// label
	'_Form name' => 'Form name',	// label
	'_New table' => 'New table',
	'_New form' => 'New form',
	'_Add table' => "Add table",
	'_Add form' => "Add form",
	'_Edit form' => 'Edit form',
	'_View form' => 'View form',
	'_New form name' => "New form name",
	'_Add a new table' => 'Add a new table',
	'_Add a new form' => 'Add a new form',
	'_Cannot list tables' => 'Cannot list tables',	// error
	'_Cannot list forms' => 'Cannot list forms',	// error
	'_Table %s' => "Table <span class='highlight'>%s</span>",	// a title
	//'_Table %s' => "Table '%s'",	// a title
	'_Form %s' => "Form <span class='highlight'>%s</span>",	// a title
	//'_Form %s' => "Form '%s'",	// a title
	'_Edit form %s' => "Edit form <span class='highlight'>%s</span>",
	'_Edit form %s!' => "Edit form '%s'",
	'_Modify table %s' => "Modify table '%s'",
	'_Delete table %s' => "Delete table '%s'", 
	'_Delete form %s' => "Delete form '%s'", 
	//'_List tables in %s' => "List tables in <span class='highlight'>%s</span>",
	'_List tables in %s' => "List tables in '%s'",
	'_List forms in %s' => "List forms in '%s'",
	//'_Remove table from %s' => "Remove the table from <span class='highlight'>%s</span>?",
	'_Remove table in %s' => "Remove the table in '%s'?",
	//'_Remove form from %s' => "Remove the form from <span class='highlight'>%s</span>?",
	'_Remove form in %s' => "Remove the form in '%s'?",
	'_Really remove table %s' => "Really remove table `%s`?",
	'_Really remove form %s' => "Really remove form `%s`?",
	//'_Tables in database %s' => "Tables in database <span class='highlight'>%s</span>",
	'_Tables in database %s' => "Tables in database '%s'",
	//'_Forms in database %s' => "Forms in database <span class='highlight'>%s</span>",
	'_Forms in database %s!' => "Forms in database '%s'",
	'_Forms in database %s' => "Forms in database <span class='highlight'>%s</span>",
	'_Cannot create table %s' => "Cannot create table '%s'",
	'_Cannot create form %s' => "Cannot create form `%s`",
	'_Cannot rename tables %s' => "Cannot rename tables '%s'",
	'_Failed to drop table %s' => "Failed to drop table `%s`",
	'_Cannot list tables in database %s' => "Cannot list tables in database '%s'",
	'_Cannot remove non-empty table %s' => "Cannot remove non-empty table '%s'",
	'_Cannot remove non-empty form %s' => "Cannot remove non-empty form '%s'",
	'_Cannot list forms in database %s' => "Cannot list forms in database '%s'",
	'_Cannot get information for table %s' => "Cannot get information for table '%s'",
	'_Cannot get information for form %s' => "Cannot get information for form '%s'",
	//'_Add table to database %s' => "Add a table to database <span class='highlight'>%s</span>",
	//"_Tables in database %s" => "Tables in database <span class='highlight'>%s</span>",
	//"_No tables in %s" => "No tables in '%s'",	// empty
	
	/* records */
	//'_Rows' => 'Rows',
	'_Records' => 'Records',
	//'_view_desc' => 'View the contents of a database table',	// desc
	//'_No rows' => 'No rows',	// empty
	'_Add record' => 'Add record',
	'_Add a new record' => 'Add a new record',
//	'_Delete row %d' => "Delete row '%d'",
	'_Delete record %s' => "Delete record '%s'",
	//'_Rows in table %s' => "Rows in table <span class='highlight'>%s</span>",
	'_Records in table %s' => "Records in table <span class='highlight'>%s</span>",
	'_Records in table %s!' => "Records in table '%s'",
	//'_Records in form %s' => "Records in form <span class='highlight'>%s</span>",
	//'_Records in form %s' => "Records in form '%s'",
	'_Really remove this record' => 'Really remove this record?',
	'_Remove record from table %s' => "Remove the record from table <span class='highlight'>%s</span>?",
	'_Really remove record with id %s' => "Really remove record with id %s?",
	'_Cannot obtain data from table %s (in database %s)' => "Cannot obtain data from table '%s' (in database '%s')",
	
	/* column */
	'_col_desc' => 'Manage a column of a database table',	// desc
	'_Columns' => 'Columns',
	//'_Add a column' => 'Add a column',
	'_Add a new column' => "Add a new column",
	'_Add a new column to %s!' => "Add a new column to '%s'",
	'_Add a new column to %s' => "Add a new column to <span class='highlight'>%s</span>",
	'_Modify column' => "Modify column",
	'_Column name' => 'Column name',	// field
	'_Change existing name' => 'Change existing name',
	'_Column new name' => 'Column new name',
	'_Data type' => 'Data type',
	'_Data length/value' => 'Data length/value',
	'_Default value' => 'Default value',
	'_default defined value' => 'as defined: default value',	// placeholder
	'_Column Collation' => 'Column Collation',
	'_Attribute' => 'Attribute',
	'_Can contain null' => 'Can contain null',
	'_Index(es)' => 'Index(es)',
	'_Auto-Increment' => 'Auto-Increment',
	//'_Add a Column to table %s' => "Add a Column to table <span class='highlight'>%s</span>",
	'_Comments' => 'Comments',
	'_Position' => 'Position',
//	'_Last' => 'Last',	// field option
	'_alpha-numeric characters' => 'alpha-numeric characters and minus(-)',	// placeholder
	'_Add column' => 'Add column',
	//'_Drop column' => 'Drop column',	// button text
	'_Move column' => 'Move column',
	'_Column %s' => "Column '%s'",
	'_Delete column %s' => "Delete column '%s'",	// title
	//'_Delete column %s from %s'
	'_Really remove column %s' => "Really remove column '%s'?",
	//'_Remove column from table %s', "Remove column from table <span class='highlight'>%s</span>",
	'_Modify column %s!' => "Modify column '%s'",
	'_Modify column %s' => "Modify column <span class='highlight'>%s</span>",
	'_Move column %s' => "Move column <span class='highlight'>%s</span>",
	//'_Columns of table %s' => "Columns of table '%s'",
	'_Columns of table %s' => "Columns of table <span class='highlight'>%s</span>",
	'_Set extra for %s' => "Set extra for '%s'",
	'_Cannot make changes to table %s' => "Cannot make changes to table '%s'",
	'_Cannot delete column %s' => "Cannot delete column '%s'",

	//'_drag_instructions' => 'Drag (or click) an element from the toolbox (on the left) to place in position here.',
	'_drag_instructions' => '<ol id="form_placeholder" class="hide-start-drag"><li>Click a form element from the toolbox (on the left).</li>  <li>It will be placed in the form (the current element will have a red dashed border).</li>  <li>Select it by clicking (it will show the resize handles - small boxes on each side and each corner that can be resized).</li>  <li>Drag it into position and/or resize it, if required .</li></ol>',
	'_Remove all elements' => 'Remove all elements',
	'_Remove all' => 'Remove all elements',
//	'_Really remove all elements' => 'Really remove all elements',

	/* Settings */
	'_sett_desc' => 'Settings',
	'_general' => 'General',
	'_general_txt' => 'General',
	'_CHARSET' => 'Global character-set',
	'_ABS_PATH' => 'Absolute path',
	'_PREFIX' => 'Database prefix',
	'_SHOW_OTHER_DBS' => 'Show others',
	'_after_SHOW_OTHER_DBS' => 'lists all databases (BuildForms and others)',
	'_default_SHOW_OTHER_DBS' => 'off',
	'_SHOW_OG_TAGS' => 'Print OG meta',
	'_after_SHOW_OG_TAGS' => 'put Open-Graph tags in the head section (for Facebook, LinkedIn, etc.)',
	'_SHOW_TWITTER_TAGS' => 'Print Twitter meta',
	'_after_SHOW_TWITTER_TAGS' => 'put Twitter tags in the head section',
	'_SHOW_ARTICLE_TAGS' => 'Print article meta',
	'_after_SHOW_ARTICLE_TAGS' => 'put article tags in the head section',
//	'_default_SHOW_OTHER_DBS' => 'off',
	'_RESERVED' => 'Reserved names',
	'_title_RESERVED' => 'database names that cannot be used',
//	'_database names that cannot be used' => 'database names that cannot be used',
	//'_after_RESERVED' => 'lists of database names that are reserved for other use',
	'_ADD_DB' => 'Add database slug',
	'_title_ADD_DB' => 'this text is added to URL ie /databases/_',
	'_ADD_COL' => 'Add db column slug',
	'_title_ADD_COL' => 'this text is added to URL ie /database/XX/table/YY/columns/_',
	'_ADD_TBL' => 'Add db table slug',
	'_title_ADD_TBL' => 'this text is added to URL ie /database/XX/tables/_',
	'_ADD_FRM' => 'Add db form slug',
	'_title_ADD_FRM' => 'this text is added to URL ie /database/XX/forms/_',
	'_ADD_ROW' => 'Add db record slug',
	'_title_ADD_ROW' => 'this text is added to URL ie /database/XX/table/YY/rows/_',
	'_notify' => 'Notify when ...',
	'_notify_txt' => 'Notify',
	'_NOTIFY_CREATE_DB' => '... create database',
	'_after_NOTIFY_CREATE_DB' => 'displays a notice when a database is created',
	'_NOTIFY_DELETE_DB' => '... delete database',
	'_after_NOTIFY_DELETE_DB' => 'displays a notice when a database is deleted',
	'_NOTIFY_CREATE_TABLE' => '... create table',
	'_after_NOTIFY_CREATE_TABLE' => 'displays a notice when a database table is created',
	'_NOTIFY_DELETE_TABLE' => '... delete table',
	'_after_NOTIFY_DELETE_TABLE' => 'displays a notice when a database table is deleted',
	'_NOTIFY_CREATE_RECORD' => '... create record',
	'_after_NOTIFY_CREATE_RECORD' => 'displays a notice when a database record is created',
	'_NOTIFY_DELETE_RECORD' => '... delete record',
	'_after_NOTIFY_DELETE_RECORD' => 'displays a notice when a database record is deleted',
	'_NOTIFY_CREATE_COLUMN' => '... create column',
	'_after_NOTIFY_CREATE_COLUMN' => 'displays a notice when a database column is created',
	'_NOTIFY_DELETE_COLUMN' => '... delete column',
	'_after_NOTIFY_DELETE_COLUMN' => 'displays a notice when a database column is deleted',
	'_debug' => 'Debug',
	'_debug_txt' => 'Debug',
	'_HEAD_PLACEHOLDERS' => 'Head comments',
	'_after_HEAD_PLACEHOLDERS' => 'wraps head sections with HTML comments',
	'_SHOW_REQUEST' => 'Page request',
	'_after_SHOW_REQUEST' => 'displays the page request variables, such as form submissions',
	'_DEBUG_SHOW_REQ' => 'Page request',
	'_showhide' => 'Display',
	'_showhide_txt' => 'Display',
	'_after_DEBUG_SHOW_REQ' => 'displays the page request variables including form responses',
	'_DEBUG_SHOW_HEAD_COMMENTS' => 'Head comments',
	'_after_DEBUG_SHOW_HEAD_COMMENTS' => 'displays HTML comments within the page head section',
	'_DEBUG_SHOW_HTTP_VARS' => 'server values',
	'_after_DEBUG_SHOW_HTTP_VARS' => 'displays the server HTTP page variables instead of the actual page content',
	'_DEBUG_HIDE_HEAD_METAS' => 'Hide head meta',
	'_after_DEBUG_HIDE_HEAD_METAS' => 'omits the meta tags from the HTML head section',
	'_DEBUG_HIDE_HEAD_STYLES' => 'Hide head styles',
	'_after_DEBUG_HIDE_HEAD_STYLES' => 'omits the style tags from the HTML head section',
	'_DEBUG_HIDE_HEAD_SCRIPTS' => 'Hide head scripts',
	'_after_DEBUG_HIDE_HEAD_SCRIPTS' => 'omits the script tags from the HTML head section',
	//'_prevention' => 'Safety',
	'_prevention' => 'Safely delete ...',
	'_prevention_txt' => 'Safety',
	'_PREVENT_DELETE_DB_IF_HAS_TABLES' => '... databases',
	'_after_PREVENT_DELETE_DB_IF_HAS_TABLES' => 'databases that contain tables will not be deleted if enabled',
	'_PREVENT_DELETE_TABLE_IF_HAS_ROWS' => '.. db tables',
	'_after_PREVENT_DELETE_TABLE_IF_HAS_ROWS' => 'database tables that contain rows will not be deleted if enabled',
	'_PREVENT_DELETE_FORM_IF_HAS_ROWS' => '... db rows',
	'_after_PREVENT_DELETE_FORM_IF_HAS_ROWS' => 'database forms that contain rows will not be deleted if enabled',
	'_PREVENT_DELETE_TABLE_IF_HAS_COLUMNS' => '... db tables',
	'_after_PREVENT_DELETE_TABLE_IF_HAS_COLUMNS' => 'database tables that contain columns will not be deleted if enabled',
	'_PREVENT_DELETE_FORM_IF_HAS_COLUMNS' => '... db forms',
	'_after_PREVENT_DELETE_FORM_IF_HAS_COLUMNS' => 'database forms that contain columns will not be deleted if enabled',
	'_SHOW_OTHER_DBS' => 'Other databases',
	'_database' => 'Database Configuration',
	'_database_txt' => 'Database Configuration',
	'_DB_TYPE' => 'Database Type',
	'_DB_HOST' => 'Database host server',
	'_default_DB_HOST' => '"localhost" or "127.0.0.1"',
	'_DB_SOCK' => 'Use as socket',
	'_after_DB_SOCK' => 'use the "Database host server" as a socket / pipe name',
	'_default_DB_SOCK' => 'off',
	'_DB_PORT' => 'Database port',
	'_default_DB_PORT' => 'empty = 3306',
	'_DB_NAME' => 'Database name',
	'_DB_USER' => 'Database user',
	'_DB_PASS' => 'User password',
	'_DB_TIME' => 'Database timout',
	'_DB_CHAR' => 'Database character-set',
	'_DB_OPTS' => 'Database options',
	'_default_DB_OPTS' => 'none',

	'_RESET' => 'Reset',
	'_Reset_all' => '<i class=\'fa-solid fa-recycle\'></i> All settings to default',

	'_Viruality' => "Viruality",
	'_Expression' => 'Expression',
	'_Privileges' => 'Privileges',
//	'_Media type' => 'Media type',
//	'_Browser display' => 'Browser display',
//	'_Browser display transformation' => 'Browser display transformation',

	'_move to database' => 'move to database',
	'_Cannot retrieve list' => 'Cannot retrieve list',
	'_Moved %s to within %s' => "Moved '%s' to within '%s'",
	'_Moved table %s from database %s to %s' => "Moved table '%s' from database '%s' to '%s'",
	'_Cannot move table %s from database %s to %s' => "Cannot move table '%s' from database '%s' to '%s'",

	'_Tried to output a form field without an id' => 'Tried to output a form field without an id',
	"_Tried to output a form field with an invalid type ('%s') - must be %s" => "Tried to output a form field with an invalid type ('%s') - must be %s",

	'_business' => 'Business',
	'_user' => 'User',
	'_document' => 'Document',
	'_journal' => 'Journal',
	'_Inserted new business %s' => 'Inserted new business "%s"',
	'_Updated business %s' => 'Updated business "%s"',
	'_with contact %s' => 'with contact %s',
	'_Business Admin' => 'Manage the businesses, Users, Documents, etc.',
	'_Business' => 'View (or edit) a Business, User, Document, etc.',

	/* user */
	'_profile' => 'Profile',
	'_signout' => 'Sign out',
	'_title_profile' => 'View/edit My profile',
	'_title_signout' => 'Log off this user',

	/* login */
	'_login' => 'Login',
	'_login_desc' => 'Login',
	'_username' => 'Username',
	'_password' => 'Password',
	'_incorrect_login' => 'Incorrect login details',
	'_password2' => 'Password Confirm',
	'_home_page' => 'Home page',
	'_not_user' => 'Sign me up',//'Register new user',//'Not registered yet?',
	'_forgotpw' => 'Forgot password',
	'_forgot_desc' => 'Forgot password',
	'_remember' => 'Remember',
	'_remember_hint' => 'Remember me on this computer',
	'_no_cookie' => 'Never - public computer',
/* time */
	'_1hr' => '1 hour',
	'_%d_hrs' => '%d hours',
/*	'_2hr' => '2 hours',
	'_3hr' => '3 hours',
	'_4hr' => '4 hours',
	'_5hr' => '5 hours',
	'_6hr' => '6 hours',
	'_10hr' => '10 hours',
	'_24hr' => '24 hours',*/
	'_1dy' => '1 day',
/*	'_48hr' => '48 hours',*/
	'_%d_dys' => '%d days',
	'_1wk' => '1 week',
	'_%d_wks' => '%d weeks',
	'_1mth' => '1 month',
	'_%d_mths' => '%d months',
	'_1yr' => '1 year',
	'_%d_yrs' => '%d years',
	'_register' => 'Register',
	'_register_next' => 'Register >',
	'_register_new_user' => 'Register new user',
	'_reg_desc' => 'Register new user',
	'_newpw' => 'New password',
	'_first' => 'First Name',
	'_last' => 'Last Name',
	'_login_as' => 'Login as (Username)',
	'_firstname' => 'First name',
	'_lastname' => 'Last name',
	'_lastnamePH' => 'Family name',
	'_email' => 'Email address',
	'_first_part_email' => 'first part of Email address',
	'_choose_password' => 'Choose a password',
	'_confirm_password' => 'Confirm the password',
	'_session_expired' => 'Session expired',
	'_invalid_username' => 'Invalid username',
	'_invalid_email' => 'Invalid email',
	'_invalid_password' => 'Invalid password',
	'_invalid_password2' => 'Invalid confirm password',
	'_password_mismatch' => 'Both passwords must be the same',
	'_uname_taken' => 'Username is not available',
	'_email_taken' => 'Email address is already registered - Login instead',
	'_rec_pw' => 'Recover password',
	'_rec_pw_btn' => 'Try recover',
	'_inactive account (%s)' => 'Inactive account (%s)',
/*	'_' => '',*/
	//'_' => '',
);
