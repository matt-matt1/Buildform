<div class="container noflex" style="width:320px">
<!--main-->
	<!--div class="panel"-->
	<form method="POST">
<?php
		//$login_form = new HTMLForm($this->login_form);
		$login_form = '';
		$login_form .= '<div class="xpanel" style="background-color:#fff;border:1px solid #333;padding:20px">'. "\n";
		//$login_form .= '<div class="pane">'. "\n";
		$login_form .= '<form id="my-login_form" method="POST">'. "\n";;
	echo Field::out(array(
			'id' => 'login',
			'type' => 'hidden',
			'name' => 'login',
			'value' => 'login',
/*			//'noLabel' => true,
			//'label' => '<i class="fas fa-user"></i>',
			'label' => 'Username',
			//'optional' => true,
			//'showOptional' => false,
			'showOptional' => true,
			//'canWipe' => true,
			'placeholder' => 'Username',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Username',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
			'rowExtra' => 'style="float:left;margin:0 1.25em"',
			'extra' => 'autofocus data-search-in="business" data-search-id="business_name"',
*/
	));
	echo Field::out(array(
			'id' => 'username',
			'type' => 'text',
			//'noLabel' => true,
			//'label' => '<i class="fas fa-user"></i>',
			'label' => 'Username',
			//'optional' => true,
			//'showOptional' => false,
			'showOptional' => true,
			//'canWipe' => true,
			'placeholder' => 'Username',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Username',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
			'extra' => 'autofocus',
	));
	echo Field::out(array(
			'id' => 'passwd',
			'type' => 'text',
			//'noLabel' => true,
			//'label' => '<i class="fas fa-lock"></i>',
			'label' => 'Password',
			'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => 'Password',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Password',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
//			'extra' => 'data-search-in="business" data-search-id="business_name"',
	));
	echo Field::out(array(
			'id' => 'remember',
			'type' => 'select',
			//'noLabel' => true,
			//'label' => '<i class="fas fa-lock"></i>',
			'label' => 'Remember',
			'optional' => true,
			'showOptional' => false,
			'options' => array(
				0 => 'never',
				1 => '1 hour',
				2 => '2 hours',
				3 => '3 hours',
				4 => '4 hours',
				5 => '5 hours',
				10 => '10 hours',
			),
			//'canWipe' => true,
			'placeholder' => 'Remember',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Remember',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
//			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-2 xcol-sm-2 col-xs-1 g-3 form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
	));
	echo Field::out(array(
			'id' => 'submit',
//			'name' => 'submit',
			'value' => 'Submit',
			'type' => 'submit',
//			'optional' => true,
//			'showOptional' => false,
			'inputWrapClass' => 'input col-md-12',
			'rowClass' => 'ml-2 col-md-5 g-2',
			'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
	));
?>
	</form>
<!--/div-->
</div>
<div class="clear"></div>
<?php
/*
		$register_form = '';
		$register_form .= '<div class="panel">'. "\n";
		//$register_form .= '<div class="pane">'. "\n";
		$register_form .= '<form id="my-register_form" method="POST">'. "\n";
		$register_form .= Field::out(array(
			'id' => 'login',
			'type' => 'hidden',
			'name' => 'login',
			'value' => 'register',
		));
		$register_form .= Field::out(array(
			'id' => 'passwd',
			'type' => 'text',
			'noLabel' => true,
			//'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => 'Password',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Password',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
//			'extra' => 'data-search-in="business" data-search-id="business_name"',
		));
		$register_form .= Field::out(array(
			'id' => 'username',
			'type' => 'text',
			'noLabel' => true,
			//'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => 'Username',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Username',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
//			'extra' => 'autofocus data-search-in="business" data-search-id="business_name"',
		));
		$register_form .= Field::out(array(
			'id' => 'submit',
//			'name' => 'submit',
			'value' => 'Submit',
			'type' => 'submit',
//			'optional' => true,
//			'showOptional' => false,
			'inputWrapClass' => 'input col-md-12',
			'rowClass' => 'ml-2 col-md-5 g-2',
			'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
		));
		$register_form .= '</form>';
		//$register_form .= '</div>';
		$register_form .= '</div>';
*/
