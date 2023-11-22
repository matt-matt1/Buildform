<?php
namespace Yuma\Database;
class SQL {
	public const COLEXTRAS = Array(
		Array(
			'title' => 'used to generate a unique identity for new rows, so that the next automatically generated value follows sequentially from the largest column value. Use the UNSIGNED attribute if possible. (Usally starting at 1 and increments by 1)',
			'text' => 'Auto Increment',//'Auto Inc.',
//			'text' => t('Auto Inc.'),
//			'text' => Lang::getInstance()->translate('Auto Inc.'),
			'value' => 'AUTO_INCREMENT',
		),
		Array(
			//'attr' => 'class="include" data-bs-toggle="modal" data-bs-target="#myModal2" id="comment" data-include="comment"',
			//'attr' => 'data-bs-toggle="modal" data-bs-target="#myModal" class="comment"',
			'title' => 'comment',
			'text' => 'comment',
			'value' => 'COMMENT',
		),
		Array(
			//'attr' => 'data-bs-toggle="modal" data-bs-target="#myModal" class="coll"',
			'title' => 'The specific character set, collation used for the data stored in this column',
			'text' => 'Character collation',
			'value' => 'COLL',
		),
		Array(
			'text' => 'virtuality',
			'placeholder' => "eg. CONCAT(first_name,' ',last_name)",
			'title' => 'Column values are not stored, but are evaluated when rows are read, immediately after any BEFORE triggers. A virtual column takes no storage',
		)
	);
	public const COLTYPES = Array(
		Array(
			'title' => 'A 4-byte integer, signed range is -2,147,483,648 to 2,147,483,647, unsigned range is 0 to 4,294,967,295',
			'value' => 'INT',
			'type' => 'number',
			'placeholder' => 'display width',
			'min' => 1,
			'max' => 255,
			'disable' => 'BINARY',
		),
		Array(
			'title' => 'A variable-length (0-65,535) string, the effective maximum length is subject to the maximum row size',
			'value' => 'VARCHAR',
			'type' => 'number',
			'placeholder' => 'characters',
			'min' => 1,
			'max' => 65535,
			'disable' => 'UNSIGNED,ZEROFILL',
		),
		Array(
			'title' => 'A TEXT column with a maximum length of 65,535 (2^16 - 1) characters, stored with a two-byte prefix indicating the length of the value in bytes',
			'value' => 'TEXT',
			'type' => 'number',
			'placeholder' => 'characters',
			'disable' => 'UNSIGNED,ZEROFILL',
			'min' => 1,
			'max' => 65535
		),
		Array(
			'title' => 'A date, supported range is 1000-01-01 to 9999-12-31',
			'value' => 'DATE',
			'type' => 'date',
			'placeholder' => 'date (yyyy-mm-dd)',
			'disable' => 'BINARY,UNSIGNED,ZEROFILL',
//			'minlength' => 10,
//			'maxlength' => 10
		),
		Array(
			'group' => Array(
				Array(
					'label' => "Numeric",
					'disable' => 'BINARY',
					'contents' => Array(
						Array(
							'title' => 'A 1-byte integer, signed range is -128 to 127, unsigned range is 0 to 255',
							'value' => 'TINYINT',
							'type' => 'number',
							'placeholder' => 'display width',
							'min' => 0,
							'max' => 255
						),
						Array(
							'title' => 'A 2-byte integer, signed range is -32,768 to 32,767, unsigned range is 0 to 65,535',
							'value' => 'SMALLINT',
							'type' => 'number',
							'placeholder' => 'display width',
							'min' => 1,
							'max' => 255
						),
						Array(
							'title' => 'A 3-byte integer, signed range is -8,388,608 to 8,388,607, unsigned range is 0 to 16,777,215',
							'value' => 'MEDIUMINT',
							'type' => 'number',
							'placeholder' => 'display width',
							'min' => 1,
							'max' => 255
						),
						Array(
							'title' => 'A 4-byte integer, signed range is -2,147,483,648 to 2,147,483,647, unsigned range is 0 to 4,294,967,295',
							'value' => 'INT',
							'type' => 'number',
							'placeholder' => 'display width',
							'min' => 1,
							'max' => 255
						),
						Array(
							'title' => 'An 8-byte integer, signed range is -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807, unsigned range is 0 to 18,446,744,073,709,551,615',
							'value' => 'BIGINT',
							'type' => 'number',
							'placeholder' => 'display width',
							'min' => 1,
							'max' => 255
						),
					Array(
						'value' => '-',
						'attr' => 'disabled="disabled"',
					),
						Array(
							'title' => 'A fixed-point number (M, D) - the maximum (total) number of digits (M) is 65 (default 10), the maximum number of decimals (digits after the decimal point) (D) is 30 (default 0)',
							'value' => 'DECIMAL',
							'type' => 'text',
							'placeholder' => '{total # digits}[, {# decimal places}]',
						),
						Array(
							'title' => 'A small floating-point number, allowable values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38. May be changed to double if single number is ued and is greater than 24',
							'value' => 'FLOAT',
							'type' => 'text',
							'placeholder' => '{total # digits}[, {# decimal places}]',
							//'type' => 'number',
							//'placeholder' => 'display width',
							//'min' => 1,
							//'max' => 255
						),
						Array(
							'title' => 'A double-precision floating-point number, allowable values are -1.7976931348623157E+308 to -2.2250738585072014E-308, 0, and 2.2250738585072014E-308 to 1.7976931348623157E+308',
							'value' => 'DOUBLE',
							'type' => 'text',
							'placeholder' => '{total # digits}[, {# decimal places}]',
						),
						Array(
							'title' => 'Synonym for DOUBLE (exception: in REAL_AS_FLOAT SQL mode it is a synonym for FLOAT)',
							'value' => 'REAL',
							'type' => 'text',
							'placeholder' => '{total # digits}[, {# decimal places}]',
						),
					Array(
						'value' => '-',
						'attr' => 'disabled="disabled"',
					),
						Array(
							'title' => 'A bit-field type (M), storing M of bits per value (default is 1, maximum is 64)',
							'value' => 'BIT',
							'type' => 'number',
							'placeholder' => 'bits',
							'default' => 1,
							'min' => 1,
							'max' => 64
						),
						Array(
							'title' => 'A synonym for TINYINT(1), a value of zero is considered false, nonzero values are considered true',
							'value' => 'BOOLEAN',
							'type' => 'number',
							'placeholder' => '0 (false) / 1 (true)',
							'min' => 0,
							'max' => 1
						),
						Array(
							'title' => 'An alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE',
							'value' => 'SERIAL',
							'type' => 'number',
							'placeholder' => 'display width',
							'min' => 1,
							'max' => 255
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "Date and time",
					'disable' => 'BINARY,UNSIGNED,ZEROFILL',
					'contents' => Array(
						Array(
							'title' => 'A date, supported range is 1000-01-01 to 9999-12-31',
							'value' => 'DATE',
							'type' => 'date',
							'placeholder' => 'date (yyyy-mm-dd)',
							//'minlength' => 10,
							//'maxlength' => 10
						),
						Array(
							'title' => 'A date and time combination, supported range is 1000-01-01 00:00:00 to 9999-12-31 23:59:59',
							'value' => 'DATETIME',
			'type' => 'datetime-local',
			'placeholder' => 'date and time (yyyy-mm-dd hh:mm:ss)',
			'default' => '2011-12-21T11:33:23Z',
			'php' => "strftime('%Y-%m-%dT%H:%M:%S', strtotime(my_datetime_input))",
			'sql' => "DATE_FORMAT(date_and_time, '%Y-%m-%dT%H:%i') AS custom_date",
//			'minlength' => 19,
//			'maxlength' => 19
						),
						Array(
							'title' => 'A timestamp, range is 1970-01-01 00:00:01 UTC to 2038-01-09 03:14:07 UTC, stored as the number of seconds since the epoch (1970-01-01 00:00:00 UTC)',
							'value' => 'TIMESTAMP',
			'try' => 'datetime',
			'type' => 'text',
			'placeholder' => 'seconds since the epoch (1970-01-01 00:00:00 UTC)',
			'default' => '2011-12-21T11:33:23Z',
//			'minlength' => 10,
//			'maxlength' => 10
						),
						Array(
							'title' => 'A time, range is -838:59:59 to 838:59:59',
							'value' => 'TIME',
			'type' => 'time',
			'placeholder' => 'time (hh:mm:ss)',
//			'minlength' => 9,
//			'maxlength' => 9
						),
						Array(
							'title' => 'A year in four-digit (4, default) or two-digit (2) format, the allowable values are 70 (1970) to 69 (2069) or 1901 to 2155 and 0000',
							'value' => 'YEAR',
			'type' => 'number',
			'placeholder' => 'year (yyyy)',
			'minlength' => 4,
			'maxlength' => 4,
			'default' => '0000',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "String",
					'disable' => 'UNSIGNED,ZEROFILL',
					'contents' => Array(
						Array(
							'title' => 'A fixed-length (0-255, default 1) string that is always right-padded with spaces to the specified length when stored',
							'value' => 'CHAR',
							'type' => 'number',
							'placeholder' => 'characters',
							'min' => 0,
							'max' => 255,
							'default' => 1
						),
						Array(
							'title' => 'A variable-length (0-65,535) string, the effective maximum length is subject to the maximum row size',
							'value' => 'VARCHAR',
							'type' => 'number',
							'placeholder' => 'characters',
							'min' => 0,
							'max' => 65535
						),
					Array(
						'value' => '-',
						'attr' => 'disabled="disabled"',
					),
						Array(
							'title' => 'A TEXT column with a maximum length of 255 (2^8 - 1) characters, stored with a one-byte prefix indicating the length of the value in bytes',
							'value' => 'TINYTEXT',
							'type' => 'number',
							'placeholder' => 'characters',
							'min' => 0,
							'max' => 255,
							'default' => 1
						),
						Array(
							'title' => 'A TEXT column with a maximum length of 65,535 (2^16 - 1) characters, stored with a two-byte prefix indicating the length of the value in bytes',
							'value' => 'TEXT',
							'type' => 'number',
							'placeholder' => 'characters',
							'min' => 0,
							'max' => 65535,
							'default' => 1
						),
						Array(
							'title' => 'A TEXT column with a maximum length of 16,777,215 (2^24 - 1) characters, stored with a three-byte prefix indicating the length of the value in bytes',
							'value' => 'MEDIUMTEXT',
							'type' => 'number',
							'placeholder' => 'characters',
							'min' => 0,
							'max' => 16777215,
							'default' => 1
						),
						Array(
							'title' => 'A TEXT column with a maximum length of 4,294,967,295 or 4GiB (2^32 - 1) characters, stored with a four-byte prefix indicating the length of the value in bytes',
							'value' => 'LONGTEXT',
							'type' => 'number',
							'placeholder' => 'characters',
							'min' => 0,
							'max' => 4294967295,
							'default' => 1
						),
					Array(
						'value' => '-',
						'attr' => 'disabled="disabled"',
					),
						Array(
							'title' => 'Similar to the CHAR type, but stores binary byte strings rather than non-binary character strings',
							'value' => 'BINARY',
							'type' => 'number',
							'placeholder' => 'bytes',
							'min' => 0,
							'max' => 255,
							'default' => 1
						),
						Array(
							'title' => 'Similar to the VARCHAR type, but stores binary byte strings rather than non-binary character strings',
							'value' => 'VARBINARY',
							'placeholder' => 'bytes',
							'min' => 0,
							'max' => 65535,
							'default' => 1
						),
					Array(
						'value' => '-',
						'attr' => 'disabled="disabled"',
					),
						Array(
							'title' => 'A BLOB column with a maximum length of 255 (2^8 - 1) bytes, stored with a one-byte prefix indicating the length of the value',
							'value' => 'TINYBLOB',
							'placeholder' => 'bytes',
							'min' => 0,
							'max' => 255,
							'default' => 1
						),
						Array(
							'title' => 'A BLOB column with a maximum length of 65,535 (2^16 - 1) bytes, stored with a two-byte prefix indicating the length of the value',
							'value' => 'BLOB',
							'placeholder' => 'bytes',
							'min' => 0,
							'max' => 65535,
							'default' => 1
						),
						Array(
							'title' => 'A BLOB column with a maximum length of 16,777,215 (2^24 - 1) bytes, stored with a three-byte prefix indicating the length of the value',
							'value' => 'MEDIUMBLOB',
							'placeholder' => 'bytes',
							'min' => 0,
							'max' => 16777215,
							'default' => 1
						),
						Array(
							'title' => 'A BLOB column with a maximum length of 4,294,967,295 or 4GiB (2^32 - 1) bytes, stored with a four-byte prefix indicating the length of the value',
							'value' => 'LONGBLOB',
							'placeholder' => 'bytes',
							'min' => 0,
							'max' => 4294967295,
							'default' => 1
						),
					Array(
						'value' => '-',
						'attr' => 'disabled="disabled"',
					),
						Array(
							'title' => 'An enumeration, selects one from the given list of up to 65,535 values or the special \'\' error value',
							'value' => 'ENUM',
							'placeholder' => 'list of strings eg. (abc, d, efgh)',
							'min' => 0,
//							'max' => 65535,
						),
						Array(
							'title' => 'A string object that can have 0 or more values, chosen from a list of possible values. You can list up to 64 values in the given list',
							'value' => 'SET',
							'placeholder' => 'list of strings eg. (abc, d, efgh)',
							'min' => 0,
//							'max' => 64,
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "Spatial",
					'contents' => Array(
						Array(
							'title' => 'A type that can store a geometry of any type',
							'value' => 'GEOMETRY'
						),
						Array(
							'title' => 'A point in 2-dimensional space',
							'value' => 'POINT'
						),
						Array(
							'title' => 'A curve with linear interpolation between points',
							'value' => 'LINESTRING'
						),
						Array(
							'title' => 'A polygon',
							'value' => 'POLYGON'
						),
						Array(
							'title' => 'A collection of points',
							'value' => 'MULTIPOINT'
						),
						Array(
							'title' => 'A collection of curves with linear interpolation between points',
							'value' => 'MULTILINESTRING'
						),
						Array(
							'title' => 'A collection of polygons',
							'value' => 'MULTIPOLYGON'
						),
						Array(
							'title' => 'A collection of geometry objects of any type',
							'value' => 'GEOMETRYCOLLECTION'
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "JSON",
					'contents' => Array(
						Array(
							'title' => 'Stores and enables efficient access to data in JSON (JavaScript Object Notation) documents',
							'value' => 'JSON'
						),
					),
				),
			),
		),
	);
//			<select lang="en" dir="ltr" name="field_collation[0]" id="field_0_5">
	public const COLLATION = Array(
/*		Array(
			'value' => "",
),*/
		Array(
			'group' => Array(
				Array(
					'label' => "armscii8",
					'title' => "ARMSCII-8 Armenian",
					'contents' => Array(
						Array(
							'value' => "armscii8_bin",
							'title' => "Armenian, binary",//'text' => 'armscii8_bin'
						),
						Array(
							'value' => "armscii8_general_ci",
							'title' => "Armenian, case-insensitive",//'text' => 'armscii8_general_ci',
						),
						Array(
							'value' => "armscii8_general_nopad_ci",
							'title' => "Armenian, no-pad, case-insensitive",//'text' => 'armscii8_general_nopad_ci'
						),
						Array(
							'value' => "armscii8_nopad_bin",
							'title' => "Armenian, no-pad, binary",//'text' => 'armscii8_nopad_bin'
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "ascii",
					'title' => "US ASCII",
					'contents' => Array(
						Array(
							'value' => 'ascii_bin',
							'title' => 'West European, binary',
							'text' => 'ascii_bin',
						),
						Array(
							'value' => 'ascii_general_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'ascii_general_ci',
						),
						Array(
							'value' => 'ascii_general_nopad_ci',
							'title' => 'West European, no-pad, case-insensitive',
							'text' => 'ascii_general_nopad_ci',
						),
						Array(
							'value' => 'ascii_nopad_bin',
							'title' => 'West European, no-pad, binary',
							'text' => 'ascii_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "big5",
					'title' => "Big5 Traditional Chinese",
					'contents' => Array(
						Array(
							'value' => 'big5_bin',
							'title' => 'Traditional Chinese, binary',
							'text' => 'big5_bin',
						),
						Array(
							'value' => 'big5_chinese_ci',
							'title' => 'Traditional Chinese, case-insensitive',
							'text' => 'big5_chinese_ci',
						),
						Array(
							'value' => 'big5_chinese_nopad_ci',
							'title' => 'Traditional Chinese, no-pad, case-insensitive',
							'text' => 'big5_chinese_nopad_ci',
						),
						Array(
							'value' => 'big5_nopad_bin',
							'title' => 'Traditional Chinese, no-pad, binary',
							'text' => 'big5_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "binary",
					'title' => "Binary pseudo charset",
					'contents' => Array(
						Array(
							'value' => 'binary',
							'title' => 'Binary',
							'text' => 'binary',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp1250",
					'title' => "Windows Central European",
					'contents' => Array(
						Array(
							'value' => 'cp1250_bin',
							'title' => 'Central European, binary',
							'text' => 'cp1250_bin',
						),
						Array(
							'value' => 'cp1250_croatian_ci',
							'title' => 'Croatian, case-insensitive',
							'text' => 'cp1250_croatian_ci',
						),
						Array(
							'value' => 'cp1250_czech_cs',
							'title' => 'Czech, case-sensitive',
							'text' => 'cp1250_czech_cs',
						),
						Array(
							'value' => 'cp1250_general_ci',
							'title' => 'Central European, case-insensitive',
							'text' => 'cp1250_general_ci',
						),
						Array(
							'value' => 'cp1250_general_nopad_ci',
							'title' => 'Central European, no-pad, case-insensitive',
							'text' => 'cp1250_general_nopad_ci',
						),
						Array(
							'value' => 'cp1250_nopad_bin',
							'title' => 'Central European, no-pad, binary',
							'text' => 'cp1250_nopad_bin',
						),
						Array(
							'value' => 'cp1250_polish_ci',
							'title' => 'Polish, case-insensitive',
							'text' => 'cp1250_polish_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp1251",
					'title' => "Windows Cyrillic",
					'contents' => Array(
						Array(
							'value' => 'cp1251_bin',
							'title' => 'Cyrillic, binary',
							'text' => 'cp1251_bin',
						),
						Array(
							'value' => 'cp1251_bulgarian_ci',
							'title' => 'Bulgarian, case-insensitive',
							'text' => 'cp1251_bulgarian_ci',
						),
						Array(
							'value' => 'cp1251_general_ci',
							'title' => 'Cyrillic, case-insensitive',
							'text' => 'cp1251_general_ci',
						),
						Array(
							'value' => 'cp1251_general_cs',
							'title' => 'Cyrillic, case-sensitive',
							'text' => 'cp1251_general_cs',
						),
						Array(
							'value' => 'cp1251_general_nopad_ci',
							'title' => 'Cyrillic, no-pad, case-insensitive',
							'text' => 'cp1251_general_nopad_ci',
						),
						Array(
							'value' => 'cp1251_nopad_bin',
							'title' => 'Cyrillic, no-pad, binary',
							'text' => 'cp1251_nopad_bin',
						),
						Array(
							'value' => 'cp1251_ukrainian_ci',
							'title' => 'Ukrainian, case-insensitive',
							'text' => 'cp1251_ukrainian_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp1256",
					'title' => "Windows Arabic",
					'contents' => Array(
						Array(
							'value' => 'cp1256_bin',
							'title' => 'Arabic, binary',
							'text' => 'cp1256_bin',
						),
						Array(
							'value' => 'cp1256_general_ci',
							'title' => 'Arabic, case-insensitive',
							'text' => 'cp1256_general_ci',
						),
						Array(
							'value' => 'cp1256_general_nopad_ci',
							'title' => 'Arabic, no-pad, case-insensitive',
							'text' => 'cp1256_general_nopad_ci',
						),
						Array(
							'value' => 'cp1256_nopad_bin',
							'title' => 'Arabic, no-pad, binary',
							'text' => 'cp1256_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp1257",
					'title' => "Windows Baltic",
					'contents' => Array(
						Array(
							'value' => 'cp1257_bin',
							'title' => 'Baltic, binary',
							'text' => 'cp1257_bin',
						),
						Array(
							'value' => 'cp1257_general_ci',
							'title' => 'Baltic, case-insensitive',
							'text' => 'cp1257_general_ci',
						),
						Array(
							'value' => 'cp1257_general_nopad_ci',
							'title' => 'Baltic, no-pad, case-insensitive',
							'text' => 'cp1257_general_nopad_ci',
						),
						Array(
							'value' => 'cp1257_lithuanian_ci',
							'title' => 'Lithuanian, case-insensitive',
							'text' => 'cp1257_lithuanian_ci',
						),
						Array(
							'value' => 'cp1257_nopad_bin',
							'title' => 'Baltic, no-pad, binary',
							'text' => 'cp1257_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp850",
					'title' => "DOS West European",
					'contents' => Array(
						Array(
							'value' => 'cp850_bin',
							'title' => 'West European, binary',
							'text' => 'cp850_bin',
						),
						Array(
							'value' => 'cp850_general_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'cp850_general_ci',
						),
						Array(
							'value' => 'cp850_general_nopad_ci',
							'title' => 'West European, no-pad, case-insensitive',
							'text' => 'cp850_general_nopad_ci',
						),
						Array(
							'value' => 'cp850_nopad_bin',
							'title' => 'West European, no-pad, binary',
							'text' => 'cp850_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp852",
					'title' => "DOS Central European",
					'contents' => Array(
						Array(
							'value' => 'cp852_bin',
							'title' => 'Central European, binary',
							'text' => 'cp852_bin',
						),
						Array(
							'value' => 'cp852_general_ci',
							'title' => 'Central European, case-insensitive',
							'text' => 'cp852_general_ci',
						),
						Array(
							'value' => 'cp852_general_nopad_ci',
							'title' => 'Central European, no-pad, case-insensitive',
							'text' => 'cp852_general_nopad_ci',
						),
						Array(
							'value' => 'cp852_nopad_bin',
							'title' => 'Central European, no-pad, binary',
							'text' => 'cp852_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp866",
					'title' => "DOS Russian",
					'contents' => Array(
						Array(
							'value' => 'cp866_bin',
							'title' => 'Russian, binary',
							'text' => 'cp866_bin',
						),
						Array(
							'value' => 'cp866_general_ci',
							'title' => 'Russian, case-insensitive',
							'text' => 'cp866_general_ci',
						),
						Array(
							'value' => 'cp866_general_nopad_ci',
							'title' => 'Russian, no-pad, case-insensitive',
							'text' => 'cp866_general_nopad_ci',
						),
						Array(
							'value' => 'cp866_nopad_bin',
							'title' => 'Russian, no-pad, binary',
							'text' => 'cp866_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "cp932",
					'title' => "SJIS for Windows Japanese",
					'contents' => Array(
						Array(
							'value' => 'cp932_bin',
							'title' => 'Japanese, binary',
							'text' => 'cp932_bin',
						),
						Array(
							'value' => 'cp932_japanese_ci',
							'title' => 'Japanese, case-insensitive',
							'text' => 'cp932_japanese_ci',
						),
						Array(
							'value' => 'cp932_japanese_nopad_ci',
							'title' => 'Japanese, no-pad, case-insensitive',
							'text' => 'cp932_japanese_nopad_ci',
						),
						Array(
							'value' => 'cp932_nopad_bin',
							'title' => 'Japanese, no-pad, binary',
							'text' => 'cp932_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "dec8",
					'title' => "DEC West European",
					'contents' => Array(
						Array(
							'value' => 'dec8_bin',
							'title' => 'West European, binary',
							'text' => 'dec8_bin',
						),
						Array(
							'value' => 'dec8_nopad_bin',
							'title' => 'West European, no-pad, binary',
							'text' => 'dec8_nopad_bin',
						),
						Array(
							'value' => 'dec8_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'dec8_swedish_ci',
						),
						Array(
							'value' => 'dec8_swedish_nopad_ci',
							'title' => 'Swedish, no-pad, case-insensitive',
							'text' => 'dec8_swedish_nopad_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "eucjpms",
					'title' => "UJIS for Windows Japanese",
					'contents' => Array(
						Array(
							'value' => 'eucjpms_bin',
							'title' => 'Japanese, binary',
							'text' => 'eucjpms_bin',
						),
						Array(
							'value' => 'eucjpms_japanese_ci',
							'title' => 'Japanese, case-insensitive',
							'text' => 'eucjpms_japanese_ci',
						),
						Array(
							'value' => 'eucjpms_japanese_nopad_ci',
							'title' => 'Japanese, no-pad, case-insensitive',
							'text' => 'eucjpms_japanese_nopad_ci',
						),
						Array(
							'value' => 'eucjpms_nopad_bin',
							'title' => 'Japanese, no-pad, binary',
							'text' => 'eucjpms_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "euckr",
					'title' => "EUC-KR Korean",
					'contents' => Array(
						Array(
							'value' => 'euckr_bin',
							'title' => 'Korean, binary',
							'text' => 'euckr_bin',
						),
						Array(
							'value' => 'euckr_korean_ci',
							'title' => 'Korean, case-insensitive',
							'text' => 'euckr_korean_ci',
						),
						Array(
							'value' => 'euckr_korean_nopad_ci',
							'title' => 'Korean, no-pad, case-insensitive',
							'text' => 'euckr_korean_nopad_ci',
						),
						Array(
							'value' => 'euckr_nopad_bin',
							'title' => 'Korean, no-pad, binary',
							'text' => 'euckr_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "gb2312",
					'title' => "GB2312 Simplified Chinese",
					'contents' => Array(
						Array(
							'value' => 'gb2312_bin',
							'title' => 'Simplified Chinese, binary',
							'text' => 'gb2312_bin',
						),
						Array(
							'value' => 'gb2312_chinese_ci',
							'title' => 'Simplified Chinese, case-insensitive',
							'text' => 'gb2312_chinese_ci',
						),
						Array(
							'value' => 'gb2312_chinese_nopad_ci',
							'title' => 'Simplified Chinese, no-pad, case-insensitive',
							'text' => 'gb2312_chinese_nopad_ci',
						),
						Array(
							'value' => 'gb2312_nopad_bin',
							'title' => 'Simplified Chinese, no-pad, binary',
							'text' => 'gb2312_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "gbk",
					'title' => "GBK Simplified Chinese",
					'contents' => Array(
						Array(
							'value' => 'gbk_bin',
							'title' => 'Simplified Chinese, binary',
							'text' => 'gbk_bin',
						),
						Array(
							'value' => 'gbk_chinese_ci',
							'title' => 'Simplified Chinese, case-insensitive',
							'text' => 'gbk_chinese_ci',
						),
						Array(
							'value' => 'gbk_chinese_nopad_ci',
							'title' => 'Simplified Chinese, no-pad, case-insensitive',
							'text' => 'gbk_chinese_nopad_ci',
						),
						Array(
							'value' => 'gbk_nopad_bin',
							'title' => 'Simplified Chinese, no-pad, binary',
							'text' => 'gbk_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "geostd8",
					'title' => "GEOSTD8 Georgian",
					'contents' => Array(
						Array(
							'value' => 'geostd8_bin',
							'title' => 'Georgian, binary',
							'text' => 'geostd8_bin',
						),
						Array(
							'value' => 'geostd8_general_ci',
							'title' => 'Georgian, case-insensitive',
							'text' => 'geostd8_general_ci',
						),
						Array(
							'value' => 'geostd8_general_nopad_ci',
							'title' => 'Georgian, no-pad, case-insensitive',
							'text' => 'geostd8_general_nopad_ci',
						),
						Array(
							'value' => 'geostd8_nopad_bin',
							'title' => 'Georgian, no-pad, binary',
							'text' => 'geostd8_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "greek",
					'title' => "ISO 8859-7 Greek",
					'contents' => Array(
						Array(
							'value' => 'greek_bin',
							'title' => 'Greek, binary',
							'text' => 'greek_bin',
						),
						Array(
							'value' => 'greek_general_ci',
							'title' => 'Greek, case-insensitive',
							'text' => 'greek_general_ci',
						),
						Array(
							'value' => 'greek_general_nopad_ci',
							'title' => 'Greek, no-pad, case-insensitive',
							'text' => 'greek_general_nopad_ci',
						),
						Array(
							'value' => 'greek_nopad_bin',
							'title' => 'Greek, no-pad, binary',
							'text' => 'greek_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "hebrew",
					'title' => "ISO 8859-8 Hebrew",
					'contents' => Array(
						Array(
							'value' => 'hebrew_bin',
							'title' => 'Hebrew, binary',
							'text' => 'hebrew_bin',
						),
						Array(
							'value' => 'hebrew_general_ci',
							'title' => 'Hebrew, case-insensitive',
							'text' => 'hebrew_general_ci',
						),
						Array(
							'value' => 'hebrew_general_nopad_ci',
							'title' => 'Hebrew, no-pad, case-insensitive',
							'text' => 'hebrew_general_nopad_ci',
						),
						Array(
							'value' => 'hebrew_nopad_bin',
							'title' => 'Hebrew, no-pad, binary',
							'text' => 'hebrew_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "hp8",
					'title' => "HP West European",
					'contents' => Array(
						Array(
							'value' => 'hp8_bin',
							'title' => 'West European, binary',
							'text' => 'hp8_bin',
						),
						Array(
							'value' => 'hp8_english_ci',
							'title' => 'English, case-insensitive',
							'text' => 'hp8_english_ci',
						),
						Array(
							'value' => 'hp8_english_nopad_ci',
							'title' => 'English, no-pad, case-insensitive',
							'text' => 'hp8_english_nopad_ci',
						),
						Array(
							'value' => 'hp8_nopad_bin',
							'title' => 'West European, no-pad, binary',
							'text' => 'hp8_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "keybcs2",
					'title' => "DOS Kamenicky Czech-Slovak",
					'contents' => Array(
						Array(
							'value' => 'keybcs2_bin',
							'title' => 'Czech-Slovak, binary',
							'text' => 'keybcs2_bin',
						),
						Array(
							'value' => 'keybcs2_general_ci',
							'title' => 'Czech-Slovak, case-insensitive',
							'text' => 'keybcs2_general_ci',
						),
						Array(
							'value' => 'keybcs2_general_nopad_ci',
							'title' => 'Czech-Slovak, no-pad, case-insensitive',
							'text' => 'keybcs2_general_nopad_ci',
						),
						Array(
							'value' => 'keybcs2_nopad_bin',
							'title' => 'Czech-Slovak, no-pad, binary',
							'text' => 'keybcs2_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "koi8r",
					'title' => "KOI8-R Relcom Russian",
					'contents' => Array(
						Array(
							'value' => 'koi8r_bin',
							'title' => 'Russian, binary',
							'text' => 'koi8r_bin',
						),
						Array(
							'value' => 'koi8r_general_ci',
							'title' => 'Russian, case-insensitive',
							'text' => 'koi8r_general_ci',
						),
						Array(
							'value' => 'koi8r_general_nopad_ci',
							'title' => 'Russian, no-pad, case-insensitive',
							'text' => 'koi8r_general_nopad_ci',
						),
						Array(
							'value' => 'koi8r_nopad_bin',
							'title' => 'Russian, no-pad, binary',
							'text' => 'koi8r_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "koi8u",
					'title' => "KOI8-U Ukrainian",
					'contents' => Array(
						Array(
							'value' => 'koi8u_bin',
							'title' => 'Ukrainian, binary',
							'text' => 'koi8u_bin',
						),
						Array(
							'value' => 'koi8u_general_ci',
							'title' => 'Ukrainian, case-insensitive',
							'text' => 'koi8u_general_ci',
						),
						Array(
							'value' => 'koi8u_general_nopad_ci',
							'title' => 'Ukrainian, no-pad, case-insensitive',
							'text' => 'koi8u_general_nopad_ci',
						),
						Array(
							'value' => 'koi8u_nopad_bin',
							'title' => 'Ukrainian, no-pad, binary',
							'text' => 'koi8u_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "latin1",
					'title' => "cp1252 West European",
					'contents' => Array(
						Array(
							'value' => 'latin1_bin',
							'title' => 'West European, binary',
							'text' => 'latin1_bin',
						),
						Array(
							'value' => 'latin1_danish_ci',
							'title' => 'Danish, case-insensitive',
							'text' => 'latin1_danish_ci',
						),
						Array(
							'value' => 'latin1_general_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'latin1_general_ci',
						),
						Array(
							'value' => 'latin1_general_cs',
							'title' => 'West European, case-sensitive',
							'text' => 'latin1_general_cs',
						),
						Array(
							'value' => 'latin1_german1_ci',
							'title' => 'German (dictionary order), case-insensitive',
							'text' => 'latin1_german1_ci',
						),
						Array(
							'value' => 'latin1_german2_ci',
							'title' => 'German (phone book order), case-insensitive',
							'text' => 'latin1_german2_ci',
						),
						Array(
							'value' => 'latin1_nopad_bin',
							'title' => 'West European, no-pad, binary',
							'text' => 'latin1_nopad_bin',
						),
						Array(
							'value' => 'latin1_spanish_ci',
							'title' => 'Spanish (modern), case-insensitive',
							'text' => 'latin1_spanish_ci',
						),
						Array(
							'value' => 'latin1_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'latin1_swedish_ci',
						),
						Array(
							'value' => 'latin1_swedish_nopad_ci',
							'title' => 'Swedish, no-pad, case-insensitive',
							'text' => 'latin1_swedish_nopad_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "latin2",
					'title' => "ISO 8859-2 Central European",
					'contents' => Array(
						Array(
							'value' => 'latin2_bin',
							'title' => 'Central European, binary',
							'text' => 'latin2_bin',
						),
						Array(
							'value' => 'latin2_croatian_ci',
							'title' => 'Croatian, case-insensitive',
							'text' => 'latin2_croatian_ci',
						),
						Array(
							'value' => 'latin2_czech_cs',
							'title' => 'Czech, case-sensitive',
							'text' => 'latin2_czech_cs',
						),
						Array(
							'value' => 'latin2_general_ci',
							'title' => 'Central European, case-insensitive',
							'text' => 'latin2_general_ci',
						),
						Array(
							'value' => 'latin2_general_nopad_ci',
							'title' => 'Central European, no-pad, case-insensitive',
							'text' => 'latin2_general_nopad_ci',
						),
						Array(
							'value' => 'latin2_hungarian_ci',
							'title' => 'Hungarian, case-insensitive',
							'text' => 'latin2_hungarian_ci',
						),
						Array(
							'value' => 'latin2_nopad_bin',
							'title' => 'Central European, no-pad, binary',
							'text' => 'latin2_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "latin5",
					'title' => "ISO 8859-9 Turkish",
					'contents' => Array(
						Array(
							'value' => 'latin5_bin',
							'title' => 'Turkish, binary',
							'text' => 'latin5_bin',
						),
						Array(
							'value' => 'latin5_nopad_bin',
							'title' => 'Turkish, no-pad, binary',
							'text' => 'latin5_nopad_bin',
						),
						Array(
							'value' => 'latin5_turkish_ci',
							'title' => 'Turkish, case-insensitive',
							'text' => 'latin5_turkish_ci',
						),
						Array(
							'value' => 'latin5_turkish_nopad_ci',
							'title' => 'Turkish, no-pad, case-insensitive',
							'text' => 'latin5_turkish_nopad_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "latin7",
					'title' => "ISO 8859-13 Baltic",
					'contents' => Array(
						Array(
							'value' => 'latin7_bin',
							'title' => 'Baltic, binary',
							'text' => 'latin7_bin',
						),
						Array(
							'value' => 'latin7_estonian_cs',
							'title' => 'Estonian, case-sensitive',
							'text' => 'latin7_estonian_cs',
						),
						Array(
							'value' => 'latin7_general_ci',
							'title' => 'Baltic, case-insensitive',
							'text' => 'latin7_general_ci',
						),
						Array(
							'value' => 'latin7_general_cs',
							'title' => 'Baltic, case-sensitive',
							'text' => 'latin7_general_cs',
						),
						Array(
							'value' => 'latin7_general_nopad_ci',
							'title' => 'Baltic, no-pad, case-insensitive',
							'text' => 'latin7_general_nopad_ci',
						),
						Array(
							'value' => 'latin7_nopad_bin',
							'title' => 'Baltic, no-pad, binary',
							'text' => 'latin7_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "macce",
					'title' => "Mac Central European",
					'contents' => Array(
						Array(
							'value' => 'macce_bin',
							'title' => 'Central European, binary',
							'text' => 'macce_bin',
						),
						Array(
							'value' => 'macce_general_ci',
							'title' => 'Central European, case-insensitive',
							'text' => 'macce_general_ci',
						),
						Array(
							'value' => 'macce_general_nopad_ci',
							'title' => 'Central European, no-pad, case-insensitive',
							'text' => 'macce_general_nopad_ci',
						),
						Array(
							'value' => 'macce_nopad_bin',
							'title' => 'Central European, no-pad, binary',
							'text' => 'macce_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "macroman",
					'title' => "Mac West European",
					'contents' => Array(
						Array(
							'value' => 'macroman_bin',
							'title' => 'West European, binary',
							'text' => 'macroman_bin',
						),
						Array(
							'value' => 'macroman_general_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'macroman_general_ci',
						),
					Array(
							'value' => 'macroman_general_nopad_ci',
							'title' => 'West European, no-pad, case-insensitive',
							'text' => 'macroman_general_nopad_ci',
						),
						Array(
							'value' => 'macroman_nopad_bin',
							'title' => 'West European, no-pad, binary',
							'text' => 'macroman_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "sjis",
					'title' => "Shift-JIS Japanese",
					'contents' => Array(
						Array(
							'value' => 'sjis_bin',
							'title' => 'Japanese, binary',
							'text' => 'sjis_bin',
						),
						Array(
							'value' => 'sjis_japanese_ci',
							'title' => 'Japanese, case-insensitive',
							'text' => 'sjis_japanese_ci',
						),
						Array(
							'value' => 'sjis_japanese_nopad_ci',
							'title' => 'Japanese, no-pad, case-insensitive',
							'text' => 'sjis_japanese_nopad_ci',
						),
						Array(
							'value' => 'sjis_nopad_bin',
							'title' => 'Japanese, no-pad, binary',
							'text' => 'sjis_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "swe7",
					'title' => "7bit Swedish",
					'contents' => Array(
						Array(
							'value' => 'swe7_bin',
							'title' => 'Swedish, binary',
							'text' => 'swe7_bin',
						),
						Array(
							'value' => 'swe7_nopad_bin',
							'title' => 'Swedish, no-pad, binary',
							'text' => 'swe7_nopad_bin',
						),
						Array(
							'value' => 'swe7_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'swe7_swedish_ci',
						),
						Array(
							'value' => 'swe7_swedish_nopad_ci',
							'title' => 'Swedish, no-pad, case-insensitive',
							'text' => 'swe7_swedish_nopad_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "tis620",
					'title' => "TIS620 Thai",
					'contents' => Array(
						Array(
							'value' => 'tis620_bin',
							'title' => 'Thai, binary',
							'text' => 'tis620_bin',
						),
						Array(
							'value' => 'tis620_nopad_bin',
							'title' => 'Thai, no-pad, binary',
							'text' => 'tis620_nopad_bin',
						),
						Array(
							'value' => 'tis620_thai_ci',
							'title' => 'Thai, case-insensitive',
							'text' => 'tis620_thai_ci',
						),
						Array(
							'value' => 'tis620_thai_nopad_ci',
							'title' => 'Thai, no-pad, case-insensitive',
							'text' => 'tis620_thai_nopad_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "ucs2",
					'title' => "UCS-2 Unicode",
					'contents' => Array(
						Array(
							'value' => 'ucs2_bin',
							'title' => 'Unicode, binary',
							'text' => 'ucs2_bin',
						),
						Array(
							'value' => 'ucs2_croatian_ci',
							'title' => 'Croatian, case-insensitive',
							'text' => 'ucs2_croatian_ci',
						),
						Array(
							'value' => 'ucs2_croatian_mysql561_ci',
							'title' => 'Croatian (MySQL 5.6.1), case-insensitive',
							'text' => 'ucs2_croatian_mysql561_ci',
						),
						Array(
							'value' => 'ucs2_czech_ci',
							'title' => 'Czech, case-insensitive',
							'text' => 'ucs2_czech_ci',
						),
						Array(
							'value' => 'ucs2_danish_ci',
							'title' => 'Danish, case-insensitive',
							'text' => 'ucs2_danish_ci',
						),
						Array(
							'value' => 'ucs2_esperanto_ci',
							'title' => 'Esperanto, case-insensitive',
							'text' => 'ucs2_esperanto_ci',
						),
						Array(
							'value' => 'ucs2_estonian_ci',
							'title' => 'Estonian, case-insensitive',
							'text' => 'ucs2_estonian_ci',
						),
						Array(
							'value' => 'ucs2_general_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'ucs2_general_ci',
						),
						Array(
							'value' => 'ucs2_general_mysql500_ci',
							'title' => 'Unicode (MySQL 5.0.0), case-insensitive',
							'text' => 'ucs2_general_mysql500_ci',
						),
						Array(
							'value' => 'ucs2_general_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'ucs2_general_nopad_ci',
						),
						Array(
							'value' => 'ucs2_german2_ci',
							'title' => 'German (phone book order), case-insensitive',
							'text' => 'ucs2_german2_ci',
						),
						Array(
							'value' => 'ucs2_hungarian_ci',
							'title' => 'Hungarian, case-insensitive',
							'text' => 'ucs2_hungarian_ci',
						),
						Array(
							'value' => 'ucs2_icelandic_ci',
							'title' => 'Icelandic, case-insensitive',
							'text' => 'ucs2_icelandic_ci',
						),
						Array(
							'value' => 'ucs2_latvian_ci',
							'title' => 'Latvian, case-insensitive',
							'text' => 'ucs2_latvian_ci',
						),
						Array(
							'value' => 'ucs2_lithuanian_ci',
							'title' => 'Lithuanian, case-insensitive',
							'text' => 'ucs2_lithuanian_ci',
						),
						Array(
							'value' => 'ucs2_myanmar_ci',
							'title' => 'Burmese, case-insensitive',
							'text' => 'ucs2_myanmar_ci',
						),
						Array(
							'value' => 'ucs2_nopad_bin',
							'title' => 'Unicode, no-pad, binary',
							'text' => 'ucs2_nopad_bin',
						),
						Array(
							'value' => 'ucs2_persian_ci',
							'title' => 'Persian, case-insensitive',
							'text' => 'ucs2_persian_ci',
						),
						Array(
							'value' => 'ucs2_polish_ci',
							'title' => 'Polish, case-insensitive',
							'text' => 'ucs2_polish_ci',
						),
						Array(
							'value' => 'ucs2_roman_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'ucs2_roman_ci',
						),
						Array(
							'value' => 'ucs2_romanian_ci',
							'title' => 'Romanian, case-insensitive',
							'text' => 'ucs2_romanian_ci',
						),
						Array(
							'value' => 'ucs2_sinhala_ci',
							'title' => 'Sinhalese, case-insensitive',
							'text' => 'ucs2_sinhala_ci',
						),
						Array(
							'value' => 'ucs2_slovak_ci',
							'title' => 'Slovak, case-insensitive',
							'text' => 'ucs2_slovak_ci',
						),
						Array(
							'value' => 'ucs2_slovenian_ci',
							'title' => 'Slovenian, case-insensitive',
							'text' => 'ucs2_slovenian_ci',
						),
						Array(
							'value' => 'ucs2_spanish2_ci',
							'title' => 'Spanish (traditional), case-insensitive',
							'text' => 'ucs2_spanish2_ci',
						),
						Array(
							'value' => 'ucs2_spanish_ci',
							'title' => 'Spanish (modern), case-insensitive',
							'text' => 'ucs2_spanish_ci',
						),
						Array(
							'value' => 'ucs2_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'ucs2_swedish_ci',
						),
						Array(
							'value' => 'ucs2_thai_520_w2',
							'title' => 'Thai (UCA 5.2.0), multi-level',
							'text' => 'ucs2_thai_520_w2',
						),
						Array(
							'value' => 'ucs2_turkish_ci',
							'title' => 'Turkish, case-insensitive',
							'text' => 'ucs2_turkish_ci',
						),
						Array(
							'value' => 'ucs2_unicode_520_ci',
							'title' => 'Unicode (UCA 5.2.0), case-insensitive',
							'text' => 'ucs2_unicode_520_ci',
						),
						Array(
							'value' => 'ucs2_unicode_520_nopad_ci',
							'title' => 'Unicode (UCA 5.2.0), no-pad, case-insensitive',
							'text' => 'ucs2_unicode_520_nopad_ci',
						),
						Array(
							'value' => 'ucs2_unicode_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'ucs2_unicode_ci',
						),
						Array(
							'value' => 'ucs2_unicode_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'ucs2_unicode_nopad_ci',
						),
						Array(
							'value' => 'ucs2_vietnamese_ci',
							'title' => 'Vietnamese, case-insensitive',
							'text' => 'ucs2_vietnamese_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "ujis",
					'title' => "EUC-JP Japanese",
					'contents' => Array(
						Array(
							'value' => 'ujis_bin',
							'title' => 'Japanese, binary',
							'text' => 'ujis_bin',
						),
						Array(
							'value' => 'ujis_japanese_ci',
							'title' => 'Japanese, case-insensitive',
							'text' => 'ujis_japanese_ci',
						),
						Array(
							'value' => 'ujis_japanese_nopad_ci',
							'title' => 'Japanese, no-pad, case-insensitive',
							'text' => 'ujis_japanese_nopad_ci',
						),
						Array(
							'value' => 'ujis_nopad_bin',
							'title' => 'Japanese, no-pad, binary',
							'text' => 'ujis_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "utf16",
					'title' => "UTF-16 Unicode",
					'contents' => Array(
						Array(
							'value' => 'utf16_bin',
							'title' => 'Unicode, binary',
							'text' => 'utf16_bin',
						),
						Array(
							'value' => 'utf16_croatian_ci',
							'title' => 'Croatian, case-insensitive',
							'text' => 'utf16_croatian_ci',
						),
						Array(
							'value' => 'utf16_croatian_mysql561_ci',
							'title' => 'Croatian (MySQL 5.6.1), case-insensitive',
							'text' => 'utf16_croatian_mysql561_ci',
						),
						Array(
							'value' => 'utf16_czech_ci',
							'title' => 'Czech, case-insensitive',
							'text' => 'utf16_czech_ci',
						),
						Array(
							'value' => 'utf16_danish_ci',
							'title' => 'Danish, case-insensitive',
							'text' => 'utf16_danish_ci',
						),
						Array(
							'value' => 'utf16_esperanto_ci',
							'title' => 'Esperanto, case-insensitive',
							'text' => 'utf16_esperanto_ci',
						),
						Array(
							'value' => 'utf16_estonian_ci',
							'title' => 'Estonian, case-insensitive',
							'text' => 'utf16_estonian_ci',
						),
						Array(
							'value' => 'utf16_general_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf16_general_ci',
						),
						Array(
							'value' => 'utf16_general_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf16_general_nopad_ci',
						),
						Array(
							'value' => 'utf16_german2_ci',
							'title' => 'German (phone book order), case-insensitive',
							'text' => 'utf16_german2_ci',
						),
						Array(
							'value' => 'utf16_hungarian_ci',
							'title' => 'Hungarian, case-insensitive',
							'text' => 'utf16_hungarian_ci',
						),
						Array(
							'value' => 'utf16_icelandic_ci',
							'title' => 'Icelandic, case-insensitive',
							'text' => 'utf16_icelandic_ci',
						),
						Array(
							'value' => 'utf16_latvian_ci',
							'title' => 'Latvian, case-insensitive',
							'text' => 'utf16_latvian_ci',
						),
						Array(
							'value' => 'utf16_lithuanian_ci',
							'title' => 'Lithuanian, case-insensitive',
							'text' => 'utf16_lithuanian_ci',
						),
						Array(
							'value' => 'utf16_myanmar_ci',
							'title' => 'Burmese, case-insensitive',
							'text' => 'utf16_myanmar_ci',
						),
						Array(
							'value' => 'utf16_nopad_bin',
							'title' => 'Unicode, no-pad, binary',
							'text' => 'utf16_nopad_bin',
						),
						Array(
							'value' => 'utf16_persian_ci',
							'title' => 'Persian, case-insensitive',
							'text' => 'utf16_persian_ci',
						),
						Array(
							'value' => 'utf16_polish_ci',
							'title' => 'Polish, case-insensitive',
							'text' => 'utf16_polish_ci',
						),
						Array(
							'value' => 'utf16_roman_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'utf16_roman_ci',
						),
						Array(
							'value' => 'utf16_romanian_ci',
							'title' => 'Romanian, case-insensitive',
							'text' => 'utf16_romanian_ci',
						),
						Array(
							'value' => 'utf16_sinhala_ci',
							'title' => 'Sinhalese, case-insensitive',
							'text' => 'utf16_sinhala_ci',
						),
						Array(
							'value' => 'utf16_slovak_ci',
							'title' => 'Slovak, case-insensitive',
							'text' => 'utf16_slovak_ci',
						),
						Array(
							'value' => 'utf16_slovenian_ci',
							'title' => 'Slovenian, case-insensitive',
							'text' => 'utf16_slovenian_ci',
						),
						Array(
							'value' => 'utf16_spanish2_ci',
							'title' => 'Spanish (traditional), case-insensitive',
							'text' => 'utf16_spanish2_ci',
						),
						Array(
							'value' => 'utf16_spanish_ci',
							'title' => 'Spanish (modern), case-insensitive',
							'text' => 'utf16_spanish_ci',
						),
						Array(
							'value' => 'utf16_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'utf16_swedish_ci',
						),
						Array(
							'value' => 'utf16_thai_520_w2',
							'title' => 'Thai (UCA 5.2.0), multi-level',
							'text' => 'utf16_thai_520_w2',
						),
						Array(
							'value' => 'utf16_turkish_ci',
							'title' => 'Turkish, case-insensitive',
							'text' => 'utf16_turkish_ci',
						),
						Array(
							'value' => 'utf16_unicode_520_ci',
							'title' => 'Unicode (UCA 5.2.0), case-insensitive',
							'text' => 'utf16_unicode_520_ci',
						),
						Array(
							'value' => 'utf16_unicode_520_nopad_ci',
							'title' => 'Unicode (UCA 5.2.0), no-pad, case-insensitive',
							'text' => 'utf16_unicode_520_nopad_ci',
						),
						Array(
							'value' => 'utf16_unicode_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf16_unicode_ci',
						),
						Array(
							'value' => 'utf16_unicode_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf16_unicode_nopad_ci',
						),
						Array(
							'value' => 'utf16_vietnamese_ci',
							'title' => 'Vietnamese, case-insensitive',
							'text' => 'utf16_vietnamese_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "utf16le",
					'title' => "UTF-16LE Unicode",
					'contents' => Array(
						Array(
							'value' => 'utf16le_bin',
							'title' => 'Unicode, binary',
							'text' => 'utf16le_bin',
						),
						Array(
							'value' => 'utf16le_general_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf16le_general_ci',
						),
						Array(
							'value' => 'utf16le_general_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf16le_general_nopad_ci',
						),
						Array(
							'value' => 'utf16le_nopad_bin',
							'title' => 'Unicode, no-pad, binary',
							'text' => 'utf16le_nopad_bin',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "utf32",
					'title' => "UTF-32 Unicode",
					'contents' => Array(
						Array(
							'value' => 'utf32_bin',
							'title' => 'Unicode, binary',
							'text' => 'utf32_bin',
						),
						Array(
							'value' => 'utf32_croatian_ci',
							'title' => 'Croatian, case-insensitive',
							'text' => 'utf32_croatian_ci',
						),
						Array(
							'value' => 'utf32_croatian_mysql561_ci',
							'title' => 'Croatian (MySQL 5.6.1), case-insensitive',
							'text' => 'utf32_croatian_mysql561_ci',
						),
						Array(
							'value' => 'utf32_czech_ci',
							'title' => 'Czech, case-insensitive',
							'text' => 'utf32_czech_ci',
						),
						Array(
							'value' => 'utf32_danish_ci',
							'title' => 'Danish, case-insensitive',
							'text' => 'utf32_danish_ci',
						),
						Array(
							'value' => 'utf32_esperanto_ci',
							'title' => 'Esperanto, case-insensitive',
							'text' => 'utf32_esperanto_ci',
						),
						Array(
							'value' => 'utf32_estonian_ci',
							'title' => 'Estonian, case-insensitive',
							'text' => 'utf32_estonian_ci',
						),
						Array(
							'value' => 'utf32_general_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf32_general_ci',
						),
						Array(
							'value' => 'utf32_general_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf32_general_nopad_ci',
						),
						Array(
							'value' => 'utf32_german2_ci',
							'title' => 'German (phone book order), case-insensitive',
							'text' => 'utf32_german2_ci',
						),
						Array(
							'value' => 'utf32_hungarian_ci',
							'title' => 'Hungarian, case-insensitive',
							'text' => 'utf32_hungarian_ci',
						),
						Array(
							'value' => 'utf32_icelandic_ci',
							'title' => 'Icelandic, case-insensitive',
							'text' => 'utf32_icelandic_ci',
						),
						Array(
							'value' => 'utf32_latvian_ci',
							'title' => 'Latvian, case-insensitive',
							'text' => 'utf32_latvian_ci',
						),
						Array(
							'value' => 'utf32_lithuanian_ci',
							'title' => 'Lithuanian, case-insensitive',
							'text' => 'utf32_lithuanian_ci',
						),
						Array(
							'value' => 'utf32_myanmar_ci',
							'title' => 'Burmese, case-insensitive',
							'text' => 'utf32_myanmar_ci',
						),
						Array(
							'value' => 'utf32_nopad_bin',
							'title' => 'Unicode, no-pad, binary',
							'text' => 'utf32_nopad_bin',
						),
						Array(
							'value' => 'utf32_persian_ci',
							'title' => 'Persian, case-insensitive',
							'text' => 'utf32_persian_ci',
						),
						Array(
							'value' => 'utf32_polish_ci',
							'title' => 'Polish, case-insensitive',
							'text' => 'utf32_polish_ci',
						),
						Array(
							'value' => 'utf32_roman_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'utf32_roman_ci',
						),
						Array(
							'value' => 'utf32_romanian_ci',
							'title' => 'Romanian, case-insensitive',
							'text' => 'utf32_romanian_ci',
						),
						Array(
							'value' => 'utf32_sinhala_ci',
							'title' => 'Sinhalese, case-insensitive',
							'text' => 'utf32_sinhala_ci',
						),
						Array(
							'value' => 'utf32_slovak_ci',
							'title' => 'Slovak, case-insensitive',
							'text' => 'utf32_slovak_ci',
						),
						Array(
							'value' => 'utf32_slovenian_ci',
							'title' => 'Slovenian, case-insensitive',
							'text' => 'utf32_slovenian_ci',
						),
						Array(
							'value' => 'utf32_spanish2_ci',
							'title' => 'Spanish (traditional), case-insensitive',
							'text' => 'utf32_spanish2_ci',
						),
						Array(
							'value' => 'utf32_spanish_ci',
							'title' => 'Spanish (modern), case-insensitive',
							'text' => 'utf32_spanish_ci',
						),
						Array(
							'value' => 'utf32_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'utf32_swedish_ci',
						),
						Array(
							'value' => 'utf32_thai_520_w2',
							'title' => 'Thai (UCA 5.2.0), multi-level',
							'text' => 'utf32_thai_520_w2',
						),
						Array(
							'value' => 'utf32_turkish_ci',
							'title' => 'Turkish, case-insensitive',
							'text' => 'utf32_turkish_ci',
						),
						Array(
							'value' => 'utf32_unicode_520_ci',
							'title' => 'Unicode (UCA 5.2.0), case-insensitive',
							'text' => 'utf32_unicode_520_ci',
						),
						Array(
							'value' => 'utf32_unicode_520_nopad_ci',
							'title' => 'Unicode (UCA 5.2.0), no-pad, case-insensitive',
							'text' => 'utf32_unicode_520_nopad_ci',
						),
						Array(
							'value' => 'utf32_unicode_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf32_unicode_ci',
						),
						Array(
							'value' => 'utf32_unicode_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf32_unicode_nopad_ci',
						),
						Array(
							'value' => 'utf32_vietnamese_ci',
							'title' => 'Vietnamese, case-insensitive',
							'text' => 'utf32_vietnamese_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "utf8",
					'title' => "UTF-8 Unicode",
					'contents' => Array(
						Array(
							'value' => 'utf8_bin',
							'title' => 'Unicode, binary',
							'text' => 'utf8_bin',
						),
						Array(
							'value' => 'utf8_croatian_ci',
							'title' => 'Croatian, case-insensitive',
							'text' => 'utf8_croatian_ci',
						),
						Array(
							'value' => 'utf8_croatian_mysql561_ci',
							'title' => 'Croatian (MySQL 5.6.1), case-insensitive',
							'text' => 'utf8_croatian_mysql561_ci',
						),
						Array(
							'value' => 'utf8_czech_ci',
							'title' => 'Czech, case-insensitive',
							'text' => 'utf8_czech_ci',
						),
						Array(
							'value' => 'utf8_danish_ci',
							'title' => 'Danish, case-insensitive',
							'text' => 'utf8_danish_ci',
						),
						Array(
							'value' => 'utf8_esperanto_ci',
							'title' => 'Esperanto, case-insensitive',
							'text' => 'utf8_esperanto_ci',
						),
						Array(
							'value' => 'utf8_estonian_ci',
							'title' => 'Estonian, case-insensitive',
							'text' => 'utf8_estonian_ci',
						),
						Array(
							'value' => 'utf8_general_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf8_general_ci',
						),
						Array(
							'value' => 'utf8_general_mysql500_ci',
							'title' => 'Unicode (MySQL 5.0.0), case-insensitive',
							'text' => 'utf8_general_mysql500_ci',
						),
						Array(
							'value' => 'utf8_general_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf8_general_nopad_ci',
						),
						Array(
							'value' => 'utf8_german2_ci',
							'title' => 'German (phone book order), case-insensitive',
							'text' => 'utf8_german2_ci',
						),
						Array(
							'value' => 'utf8_hungarian_ci',
							'title' => 'Hungarian, case-insensitive',
							'text' => 'utf8_hungarian_ci',
						),
						Array(
							'value' => 'utf8_icelandic_ci',
							'title' => 'Icelandic, case-insensitive',
							'text' => 'utf8_icelandic_ci',
						),
						Array(
							'value' => 'utf8_latvian_ci',
							'title' => 'Latvian, case-insensitive',
							'text' => 'utf8_latvian_ci',
						),
						Array(
							'value' => 'utf8_lithuanian_ci',
							'title' => 'Lithuanian, case-insensitive',
							'text' => 'utf8_lithuanian_ci',
						),
						Array(
							'value' => 'utf8_myanmar_ci',
							'title' => 'Burmese, case-insensitive',
							'text' => 'utf8_myanmar_ci',
						),
						Array(
							'value' => 'utf8_nopad_bin',
							'title' => 'Unicode, no-pad, binary',
							'text' => 'utf8_nopad_bin',
						),
						Array(
							'value' => 'utf8_persian_ci',
							'title' => 'Persian, case-insensitive',
							'text' => 'utf8_persian_ci',
						),
						Array(
							'value' => 'utf8_polish_ci',
							'title' => 'Polish, case-insensitive',
							'text' => 'utf8_polish_ci',
						),
						Array(
							'value' => 'utf8_roman_ci',
							'title' => 'West European, case-insensitive',
							'text' => 'utf8_roman_ci',
						),
						Array(
							'value' => 'utf8_romanian_ci',
							'title' => 'Romanian, case-insensitive',
							'text' => 'utf8_romanian_ci',
						),
						Array(
							'value' => 'utf8_sinhala_ci',
							'title' => 'Sinhalese, case-insensitive',
							'text' => 'utf8_sinhala_ci',
						),
						Array(
							'value' => 'utf8_slovak_ci',
							'title' => 'Slovak, case-insensitive',
							'text' => 'utf8_slovak_ci',
						),
						Array(
							'value' => 'utf8_slovenian_ci',
							'title' => 'Slovenian, case-insensitive',
							'text' => 'utf8_slovenian_ci',
						),
						Array(
							'value' => 'utf8_spanish2_ci',
							'title' => 'Spanish (traditional), case-insensitive',
							'text' => 'utf8_spanish2_ci',
						),
						Array(
							'value' => 'utf8_spanish_ci',
							'title' => 'Spanish (modern), case-insensitive',
							'text' => 'utf8_spanish_ci',
						),
						Array(
							'value' => 'utf8_swedish_ci',
							'title' => 'Swedish, case-insensitive',
							'text' => 'utf8_swedish_ci',
						),
						Array(
							'value' => 'utf8_thai_520_w2',
							'title' => 'Thai (UCA 5.2.0), multi-level',
							'text' => 'utf8_thai_520_w2',
						),
						Array(
							'value' => 'utf8_turkish_ci',
							'title' => 'Turkish, case-insensitive',
							'text' => 'utf8_turkish_ci',
						),
						Array(
							'value' => 'utf8_unicode_520_ci',
							'title' => 'Unicode (UCA 5.2.0), case-insensitive',
							'text' => 'utf8_unicode_520_ci',
						),
						Array(
							'value' => 'utf8_unicode_520_nopad_ci',
							'title' => 'Unicode (UCA 5.2.0), no-pad, case-insensitive',
							'text' => 'utf8_unicode_520_nopad_ci',
						),
						Array(
							'value' => 'utf8_unicode_ci',
							'title' => 'Unicode, case-insensitive',
							'text' => 'utf8_unicode_ci',
						),
						Array(
							'value' => 'utf8_unicode_nopad_ci',
							'title' => 'Unicode, no-pad, case-insensitive',
							'text' => 'utf8_unicode_nopad_ci',
						),
						Array(
							'value' => 'utf8_vietnamese_ci',
							'title' => 'Vietnamese, case-insensitive',
							'text' => 'utf8_vietnamese_ci',
						),
					),
				),
			),
		),
		Array(
			'group' => Array(
				Array(
					'label' => "utf8mb4",
					'title' => "UTF-8 Unicode",
					'contents' => Array(
						Array(
							'value' => 'utf8mb4_bin',
							'title' => 'Unicode (UCA 4.0.0), binary',
							'text' => 'utf8mb4_bin',
						),
						Array(
							'value' => 'utf8mb4_croatian_ci',
							'title' => 'Croatian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_croatian_ci',
						),
						Array(
							'value' => 'utf8mb4_croatian_mysql561_ci',
							'title' => 'Croatian (MySQL 5.6.1), case-insensitive',
							'text' => 'utf8mb4_croatian_mysql561_ci',
						),
						Array(
							'value' => 'utf8mb4_czech_ci',
							'title' => 'Czech (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_czech_ci',
						),
						Array(
							'value' => 'utf8mb4_danish_ci',
							'title' => 'Danish (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_danish_ci',
						),
						Array(
							'value' => 'utf8mb4_esperanto_ci',
							'title' => 'Esperanto (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_esperanto_ci',
						),
						Array(
							'value' => 'utf8mb4_estonian_ci',
							'title' => 'Estonian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_estonian_ci',
						),
						Array(
							'value' => 'utf8mb4_general_ci',
							'title' => 'Unicode (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_general_ci',
						),
						Array(
							'value' => 'utf8mb4_general_nopad_ci',
							'title' => 'Unicode (UCA 4.0.0), no-pad, case-insensitive',
							'text' => 'utf8mb4_general_nopad_ci',
						),
						Array(
							'value' => 'utf8mb4_german2_ci',
							'title' => 'German (phone book order) (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_german2_ci',
						),
						Array(
							'value' => 'utf8mb4_hungarian_ci',
							'title' => 'Hungarian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_hungarian_ci',
						),
						Array(
							'value' => 'utf8mb4_icelandic_ci',
							'title' => 'Icelandic (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_icelandic_ci',
						),
						Array(
							'value' => 'utf8mb4_latvian_ci',
							'title' => 'Latvian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_latvian_ci',
						),
						Array(
							'value' => 'utf8mb4_lithuanian_ci',
							'title' => 'Lithuanian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_lithuanian_ci',
						),
						Array(
							'value' => 'utf8mb4_myanmar_ci',
							'title' => 'Burmese (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_myanmar_ci',
						),
						Array(
							'value' => 'utf8mb4_nopad_bin',
							'title' => 'Unicode (UCA 4.0.0), no-pad, binary',
							'text' => 'utf8mb4_nopad_bin',
						),
						Array(
							'value' => 'utf8mb4_persian_ci',
							'title' => 'Persian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_persian_ci',
						),
						Array(
							'value' => 'utf8mb4_polish_ci',
							'title' => 'Polish (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_polish_ci',
						),
						Array(
							'value' => 'utf8mb4_roman_ci',
							'title' => 'West European (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_roman_ci',
						),
						Array(
							'value' => 'utf8mb4_romanian_ci',
							'title' => 'Romanian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_romanian_ci',
						),
						Array(
							'value' => 'utf8mb4_sinhala_ci',
							'title' => 'Sinhalese (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_sinhala_ci',
						),
						Array(
							'value' => 'utf8mb4_slovak_ci',
							'title' => 'Slovak (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_slovak_ci',
						),
						Array(
							'value' => 'utf8mb4_slovenian_ci',
							'title' => 'Slovenian (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_slovenian_ci',
						),
						Array(
							'value' => 'utf8mb4_spanish2_ci',
							'title' => 'Spanish (traditional) (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_spanish2_ci',
						),
						Array(
							'value' => 'utf8mb4_spanish_ci',
							'title' => 'Spanish (modern) (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_spanish_ci',
						),
						Array(
							'value' => 'utf8mb4_swedish_ci',
							'title' => 'Swedish (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_swedish_ci',
						),
						Array(
							'value' => 'utf8mb4_thai_520_w2',
							'title' => 'Thai (UCA 5.2.0), multi-level',
							'text' => 'utf8mb4_thai_520_w2',
						),
						Array(
							'value' => 'utf8mb4_turkish_ci',
							'title' => 'Turkish (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_turkish_ci',
						),
						Array(
							'value' => 'utf8mb4_unicode_520_ci',
							'title' => 'Unicode (UCA 5.2.0), case-insensitive',
							'text' => 'utf8mb4_unicode_520_ci',
						),
						Array(
							'value' => 'utf8mb4_unicode_520_nopad_ci',
							'title' => 'Unicode (UCA 5.2.0), no-pad, case-insensitive',
							'text' => 'utf8mb4_unicode_520_nopad_ci',
						),
						Array(
							'value' => 'utf8mb4_unicode_ci',
							'title' => 'Unicode (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_unicode_ci',
						),
						Array(
							'value' => 'utf8mb4_unicode_nopad_ci',
							'title' => 'Unicode (UCA 4.0.0), no-pad, case-insensitive',
							'text' => 'utf8mb4_unicode_nopad_ci',
						),
						Array(
							'value' => 'utf8mb4_vietnamese_ci',
							'title' => 'Vietnamese (UCA 4.0.0), case-insensitive',
							'text' => 'utf8mb4_vietnamese_ci',
						),
					),
				),
			),
		),
      //</select>
	);
	public const COLDEF = Array(
		Array(
			'value' => 'none',
		),
		Array(
			'text' => 'as defined: (next box)',
			'value' => 'define',
			'attr' => 'class="endis endis_id_Default-input"',
		),
		Array(
			'value' => 'NULL',
		),
		Array(
			'value' => 'CURRENT_TIMESTAMP',
		),
	);
	public const COLATTR = Array(
		Array(
			'text' => 'binary',
			'value' => 'BINARY',
			'title' => 'Short for COLLATE {CHARACTER SET}_bin. Ie. applies the collation as *_bin - where * is the character set',
		),
		Array(
			'text' => 'unsigned',
			'value' => 'UNSIGNED',
		),
		Array(
			'text' => 'unsigned zerofill',
			'value' => 'ZEROFILL',
		),
	/*	Array(
			'value' => 'current timestamp',
			'value' => 'CURRENT_TIMESTAMP',
		),*/
	);
	public const COLINDEX = Array(
//		'title' => 'A database index is a data structure that improves the speed of operations in a table, such as to quickly find rows with specific column values',
		Array(
			'value' => 'PRI',
			'text' => 'Primary',
			'title' => 'uniquely identifies each record in a table. The key can only be applied once per table but may be spread over muliple columns',
			'name' => 'PRIMARY KEY',
		),
		Array(
			'value' => 'UNQ',
			'tit;e' => 'ensures that all values in a column are different. A PRIMARY KEY constraint automatically has a UNIQUE constraint. You can have many UNIQUE constraints per table',
			'text' => 'Unique',
		),
		Array(
			'value' => 'IND',
			'tit;e' => 'simple, regular or normal, the specified column values do not require to be unique and as well as can be NULL',
			'text' => 'Index',
		),
		Array(
			'value' => 'FUL',
			'tit;e' => 'allows the MATCH(<columns to search>) ... AGAINST(<search term>) syntax to search for text within the indexed column',
			'text' => 'Fulltext',
		),
		Array(
			'value' => 'SPA',
			'title' => 'creates an R-tree index (must be declared NOT NULL)',
			'eg' => 'CREATE TABLE geom (g GEOMETRY NOT NULL SRID 4326, SPATIAL INDEX(g)); -or- CREATE TABLE geom (g GEOMETRY NOT NULL SRID 4326, SPATIAL INDEX(g)); -or- CREATE SPATIAL INDEX g ON geom (g);',
			'text' => 'Spatial',
		),
		Array(
			'value' => 'DESC',
			'tit;e' => 'is a consistent index stored in the inverse order. Searching is done when the most effectual scan order',
			'text' => 'Descending',
		),
	);
			//'tit;e' => '',
	public const VIRUALITY = Array(
		Array(
			'text' => 'None',
			'title' => 'a column in a table that cannot explicitly be set to a specific value. Instead, its value is automatically generated based on an expression',
			'value' => '',
		),
		Array(
			'text' => 'virtual',
			'placeholder' => "eg. CONCAT(first_name,' ',last_name)",
			'title' => 'Column values are not stored, but are evaluated when rows are read, immediately after any BEFORE triggers. A virtual column takes no storage',
			'attr' => 'class="endis endis_id_virte"',
			'class' => 'endis endis_id_virte'
		),
		Array(
			'text' => 'persistent',
			'title' => 'a.k.a. STORED',
			'attr' => 'class="endis endis_id_virte"',
			'class' => 'endis endis_id_virte'
		),
		Array(
			'text' => 'stored',
			'title' => 'Column values are evaluated and stored when rows are inserted or updated. A stored column does require storage space and can be indexed',
			'attr' => 'class="endis endis_id_virte"',
			'class' => 'endis endis_id_virte'
		),
	);
	public const DEFAULTVAL = Array(
		Array(
			'text' => 'None',
			'value' => '',
		),
		Array(
			'text' => 'as defined',
		),
		Array(
			'text' => 'Current TIMESTAMP',
		),
	);
/*	public const MIME = Array(
		Array(
			'text' => '&nbsp;',
			'value' => ''
		),
		Array(
			'value' => "Image_JPEG",
			'text' => 'image/jpeg'
		),
		Array(
			'value' => "Text_Plain",
			'text' => 'text/plain'
		),
		Array(
            'value' => "Application_Octetstream",
			'text' => 'application/octetstream'
		),
		Array(
            'value' => "Image_PNG",
			'text' => 'image/png'
		),
		Array(
			'value' => "Text_Octetstream",
			'text' => 'text/octetstream'
		),
	);*/
/*
	public const BROWSERDISTRANS = Array(
		Array(
			'value' => "",
			'title' => "None",
		),
		Array(
			'value' => "Output/Application_Octetstream_Download.php",
			'title' => "Displays a link to download the binary data of the column. You can use the first option to specify the filename, or use the second option as the name of a column which contains the filename. If you use the second option, you need to set the first option to the empty string.",
			'text' => "Download (application/octetstream: Download)"
		            ),
		Array(
			'value' => "Output/Application_Octetstream_Hex.php",
			'title' => "Displays hexadecimal representation of data. Optional first parameter specifies how often space will be added (defaults to 2 nibbles).",
			'text' => "Hex (application/octetstream: Hex)"
		            ),
		Array(
			'value' => "Output/Image_JPEG_Inline.php",
			'title' => "Displays a clickable thumbnail. The options are the maximum width and height in pixels. The original aspect ratio is preserved.",
			'text' => "Inline (image/jpeg: Inline)"
		            ),
		Array(
			'value' => "Output/Image_JPEG_Link.php",
			'title' => "Displays a link to download this image.",
			'text' => "ImageLink (image/jpeg: Link)"
		            ),
		Array(
			'value' => "Output/Image_PNG_Inline.php",
			'title' => "Displays a clickable thumbnail. The options are the maximum width and height in pixels. The original aspect ratio is preserved.",
			'text' => "Inline (image/png: Inline)"
		            ),
		Array(
			'value' => "Output/Text_Octetstream_Sql.php",
			'title' => "Formats text as SQL query with syntax highlighting.",
			'text' => "SQL (text/octetstream: Sql)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Binarytoip.php",
			'title' => "Converts an Internet network address stored as a binary string into a string in Internet standard (IPv4/IPv6) format.",
			'text' => "Binary To IPv4/IPv6 (text/plain: Binarytoip)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Bool2Text.php",
			'title' => "Converts Boolean values to text (default 'T' and 'F'). First option is for TRUE, second for FALSE. Nonzero=true.",
			'text' => "Bool2Text (text/plain: Bool2Text)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Dateformat.php",
			'title' => "Displays a TIME, TIMESTAMP, DATETIME or numeric unix timestamp column as formatted date. The first option is the offset (in hours) which will be added to the timestamp (Default: 0). Use second option to specify a different date/time format string. Third option determines whether you want to see local date or UTC one (use &quot;local&quot; or &quot;utc&quot; strings) for that. According to that, date format has different value - for &quot;local&quot; see the documentation for PHP's strftime() function and for &quot;utc&quot; it is done using gmdate() function.",
			'text' => "Date Format (text/plain: Dateformat)"
		            ),
		Array(
			'value' => "Output/Text_Plain_External.php",
			'title' => "LINUX ONLY: Launches an external application and feeds it the column data via standard input. Returns the standard output of the application. The default is Tidy, to pretty-print HTML code. For security reasons, you have to manually edit the file libraries/classes/Plugins/Transformations/Abs/ExternalTransformationsPlugin.php and list the tools you want to make available. The first option is then the number of the program you want to use. The second option should be blank for historical reasons. The third option, if set to 1, will convert the output using htmlspecialchars() (Default 1). The fourth option, if set to 1, will prevent wrapping and ensure that the output appears all on one line (Default 1).",
			'text' => "External (text/plain: External)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Formatted.php",
			'title' => "Displays the contents of the column as-is, without running it through htmlspecialchars(). That is, the column is assumed to contain valid HTML.",
			'text' => "Formatted (text/plain: Formatted)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Imagelink.php",
			'title' => "Displays an image and a link; the column contains the filename. The first option is a URL prefix like &quot;https://www.example.com/&quot;. The second and third options are the width and the height in pixels.",
			'text' => "Image Link (text/plain: Imagelink)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Json.php",
			'title' => "Formats text as JSON with syntax highlighting.",
			'text' => "JSON (text/plain: Json)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Sql.php",
			'title' => "Formats text as SQL query with syntax highlighting.",
			'text' => "SQL (text/plain: Sql)"
		            ),
		Array(
			'value' => "Output/Text_Plain_Xml.php",
			'title' => "Formats text as XML with syntax highlighting.",
			'text' => "XML (text/plain: Xml)"
		            ),
		Array(
			'value' => "Text_Plain_Link.php",
			'title' => "Displays a link; the column contains the filename. The first option is a URL prefix like &quot;https://www.example.com/&quot;. The second option is a title for the link.",
			'text' => "TextLink (text/plain: Link)"
		            ),
		Array(
			'value' => "Text_Plain_Longtoipv4.php",
			'title' => "Converts an (IPv4) Internet network address stored as a BIGINT into a string in Internet standard dotted format.",
			'text' => "Long To IPv4 (text/plain: Longtoipv4)"
		            ),
		Array(
			'value' => "Text_Plain_PreApPend.php",
			'title' => "Prepends and/or Appends text to a string. First option is text to be prepended, second is appended (enclosed in single quotes, default empty string).",
			'text' => "PreApPend (text/plain: PreApPend)"
		            ),
		Array(
			'value' => "Text_Plain_Substring.php",
			'title' => "Displays a part of a string. The first option is the number of characters to skip from the beginning of the string (Default 0). The second option is the number of characters to return (Default: until end of string). The third option is the string to append and/or prepend when truncation occurs (Default: &quot;…&quot;).",
			'text' => "Substring (text/plain: Substring)"
		),
	);
 */
}
