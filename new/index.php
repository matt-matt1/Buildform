<?php
	if (Doc::inHTML('lang="en"')) {
		//echo "Doc::inHTML - could not set opening html tag\n";
	}
	//Link::style( "home", "css/home.css", filemtime("css/home.css") );
	Meta::add('charutf8',	'charset="utf-8"');
	Meta::add('text',		'http-equiv',	"content-type",		"text/html; charset=utf-8");
	Meta::add('edge',		'http-equiv',	"X-UA-Compatible",	"ie=edge");
	//Meta::add('viewport',	'name',			"viewport",			"width=device-width, initial-scale=1");
	echo makeHead( "Welcome to BuildForm" );
?>
<body>
<?php
$menu = new ButtonMenu([
	new Button('first', 'slug1'),
	new Button('second', 'slug2'),
	new Button('thrid', 'slug3')]);
echo $menu->HTML();
?>
</body>
</html>
<?php
	ini_set( 'display_errors', 'off' );
