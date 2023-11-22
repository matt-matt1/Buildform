<?php
namespace Yuma;
	class Note {
		const notice = "notice";//-1;
		const warning = "warning";//-2;
		const error = "error";//-3;
		private $defaults = Array(
			'title' => "",
			'message' => "",
			'canIgnore' => false,
		);
		private $params = Array();
//		public $preserve_query = false;
	
		public function __construct( array $params ) {
			$this->defaults['type'] = static::notice;
			$this->defaults['ok'] = t("_OK");
			$this->defaults['ignore'] = t('_Ignore');
	/*		if ($params['type'] == Notice::notice
				|| $params['type'] == Notice::warning
				|| $params['type'] == Notice::error
			) {*/
				//$this->params = (array)$this->defaults + (array)$params;
				$this->params = array_merge($this->defaults, (array)$params);
		unset($this->defaults);
	/*		} else {
				throw new Exception('No valid type parameter for Notice ('. $params['type']. ')');
				}*/
		}
	
		public function display() {
	/*		if (!isset($this->params)) {
				//throw new Exception('No valid parameters for Notice type ('. $this->params['type']. ')');
				throw new Exception('No valid parameters for Notice');
		}*/
	//			$this->params = (array)$this->defaults + (array)$params;
	//		echo 'defaults: '. print_r($this->defaults, true);
	//		echo 'params.: '. print_r($this->params, true);
			if (isset($_POST['pressed'])) {
				Redirect( BASE. 'databases' );
			}
			if (/*!$preserve_query ||*/ !isset($this->params['preserve_query']) || !$this->params['preserve_query']) {	// remove query part
				//$div = ((strpos(REQUEST_URI, '?') !== false) ? explode('?', $_GET) : (strpos(REQUEST_URI, '&') !== false)) ? explode('&', $_GET) : $_GET;
			//$_GET -= parse_url($_GET, PHP_URL_QUERY);
				//REQUEST_URI//$_GET = isset($div[0]) ? $div[0] : $div;
			}
	?>
		<form id="Note" name="Note" method="post"<?php
			if (isset($this->params['redirect'])) {
				echo ' action="'. $this->params['redirect'];
				$skip = Array(
					//'0',
					'redirect',
					'message',
					'type',
					'ok',
					'canIgnore',
					'ignore',
				);
				foreach($skip as $k) {
					if (in_array($k, array_keys((array)$_GET))) {
						unset($_GET[$k]);
					}
				}
				if (!empty($_GET)) {
					$q = '?';
					foreach($_GET as $k => $v) {
						$q .= "{$k}={$v}&";
					}
					echo do_mbstr('substr', $q, 0, -1);
				}
				echo '"';
			}
			echo '>'. "\n";
			if (isset($this->params['post']))
			{
				foreach ($this->params['post'] as $k => $v)
				{
	?>
			<input type="hidden" name="<?php echo $k; ?>" value="<?php echo urldecode($v); ?>">
	<?php
				}
			}
	?>
			<div class="note <?php echo $this->params['type']; ?>">
	<?php
			if (!isset($this->params['title']) || empty($this->params['title'])) {
				$this->params['title'] = strtoupper($this->params['type']);
			}
	?>
				<div class="title"><?php echo $this->params['title']; ?></div>
				<div class="message"><?php echo $this->params['message']; ?></div>
	<?php
			if (isset($this->params['details']) && !empty($this->params['details'])) {
	?>
				<details>
					<summary><?php l('_details'); ?></summary>
					<p class="details"><?php echo $this->params['details']; ?></p>
				</details>
	<?php
			}
	?>
				<div class="btns">
					<button class="ok" name="pressed" value="ok"<?php if (isset($this->params['okSlug']) && !empty($this->params['okSlug'])) { echo ' dataset-action="'. $this->params['okSlug']. '"'; }?>><?php echo $this->params['ok']; ?></button>
	<?php if (/*(isset($this->params['ignore']) && !empty($this->params['ignore'])) ||*/ (isset($this->params['canIgnore']) && $this->params['canIgnore'])) { ?>
					<button class="ignore<?php if (isset($this->params['ignore_class'])) { print ' '. $this->params['ignore_class']; } ?>" name="pressed" value="ignore"<?php if (isset($this->params['ignoreSlug']) && !empty($this->params['ignoreSlug'])) { echo ' dataset-action="'. $this->params['ignoreSlug']. '"'; }?>><?php echo $this->params['ignore']; ?></button>
	<?php } ?>
				</div>
			</div>
		</form>
	<?php
		}
		
		public function set(array $array)
		{
			foreach ($array as $propertyToSet => $value) {
				$this->params[$propertyToSet] = $value;
//				$this->propertyToSet = $value;
			}
/*			$refl = new ReflectionClass($this);
			echo 'ReflectionClass<pre>'. print_r($refl, true). "</pre>";
		
			foreach ($array as $propertyToSet => $value) {
				try {
					$property = $refl->getProperty($propertyToSet);
		
					if ($property instanceof ReflectionProperty) {
			echo 'ReflectionProperty<pre>'. print_r($property, true). "</pre>";
					echo '<pre>'. print_r($refl->getProperties(ReflectionProperty::IS_PRIVATE), true). "</pre>";
						$property->params->setValue($this, $value);
					}
				} catch (ReflectionException $e) {
					echo 'ReflectionExceptions<pre>'. print_r($e, true). "</pre>";
					echo '<pre>'. print_r($refl->getProperties(ReflectionProperty::IS_PRIVATE), true). "</pre>";
				}
			}*/
		}

		public function getParams()
		{
			return $this->params;
		}

	
	}

