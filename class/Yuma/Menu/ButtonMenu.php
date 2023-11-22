<?php
class ButtonMenu {
/*	public $vertical = true;
	public $class = "";
	public $content = Array();
	public $newlines = true;
	public $indents = 0;*/
	private $defaults = Array(
		'vertical' => true,
		'class' => "",
		'content' => Array(),
		'newlines' => true,
		'indents' => 0,
		'id' => "",
		'size' => 0,
		'maxItems' => 0,
		'fillBlanks' => false
	);
	public $params = Array();

    /**
	 * @param Button[] $btnc
	 */
	//public function __construct( array $btns, bool $newlines=true, int $indents=1, bool $canAdd=false, string $wrap='', string $class="" ) {
	public function __construct( array $params ) {
		$this->params = array_merge( $this->defaults, $params );
		unset($this->defaults);
/*		$this->content = $btns;
		$this->newlines = $newlines;
		$this->indents = $indents;
		$this->canAdd = $canAdd;
		$this->wrap = $wrap;
		$this->class = $class;*/
	}

	public function add( Button $btn ) {
		//$this->content[] = $btn;
		$this->params['content'][] = $btn;
	}

	public function HTML() {
		$out = '';
		//$out = print_r($this->params, true);
		if (isset($this->params['indents']) /*&& $this->indents && is_numeric($this->indents)*/) {
			//if (is_bool($this->indents)) {
			//	$this->indents = 1;
			//}
			if (is_numeric($this->params['indents'])) {
				$out .= str_repeat( "\t", $this->params['indents'] );
			} else {
				$out .= "\t";
			}
		}
		if (isset($this->params['wrap']) && !empty($this->params['wrap'])) {
			$out .= '<'. $this->params['wrap']. '>';
		}
		$out .= '<ul class="btnMenu';
		$out .= ' '. (($this->params['vertical']) ? 'vert' : 'horz');
		if (isset($this->params['class']) && !empty($this->params['class'])) {
			$out .= ' '. htmlspecialchars($this->params['class']);
		}
		$out .= '">';
		if (isset($this->params['newlines']) && $this->params['newlines']) {
			$out .= "\n";
		}
		foreach( $this->params['content'] as $btn ) {
			$out .= $btn->HTML();
			if (isset($this->params['newlines']) && $this->params['newlines']) {
				$out .= "\n";
			}
		}
		if (isset($this->params['imaxItems']) && $this->params['maxItems'] > 0) {
			for( $i=count($this->params['content']); $i<$this->params['maxItems']; $i++ ) {
				$out .= '<li>&nbsp</li>';//'<tr><td>&nbsp;</td></tr>';
				if (isset($this->params['newlines']) && $this->params['newlines']) {
					$out .= "\n";
				}
			}
		}
		if (isset($this->params['indents']) && $this->params['indents'] /*&& is_numeric($this->indents)*/) {
			//if (is_bool($this->indents)) {
			//	$this->indents = 1;
			//}
			if (is_numeric($this->params['indents'])) {
				$out .= str_repeat( "\t", $this->params['indents'] );
			} else {
				$out .= "\t";
			}
		}
		$out .= '</ul>';
		if (isset($this->params['canAdd']) && $this->params['canAdd']) {
			$out .= "\n";
			if (isset($this->params['indents']) && $this->params['indents'] /*&& is_numeric($this->indents)*/) {
				//if (is_bool($this->indents)) {
				//	$this->indents = 1;
				//}
				if (is_numeric($this->params['indents'])) {
					$out .= str_repeat( "\t", $this->params['indents'] );
				} else {
					$out .= "\t";
				}
			}
			$out .= '<span class="';
			if (isset($this->params['class']) && !empty($this->params['class'])) {
				$out .= htmlspecialchars($this->params['class']). ' ';
			}
			$out .= 'addBtn"></span>';//. "\n";
		}
		if (isset($this->params['wrap']) && !empty($this->params['wrap'])) {
			$out .= '</'. strtok($this->params['wrap'], ' '). '>';
		}
		return $out. PHP_EOL;
	}

}
