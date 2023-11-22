<?php
class Menu {
	//public $maxWidth = 150;
	public $vertical = true;
	//public $textAlign = TextAlign::CENTER;
	public $class = "";
	public $content = Array();

    /**
	 * @param Button[] $btnc
	 */
	public function __construct(array $btns) {
		$this->content = $btns;
	}

    /**
	 * @param array $names
	 */
	public function classSet(array $names) {
		$this->class = implode(' ', $names);
	}

	public function add(Button $btn) {
		$this->content[] = $btn;
	}

	public function HTML() {
		$out = '<ul';
		$out .= ' class="'. (($this->vertical) ? 'vert' : 'horz');
		if (isset($this->class) && !empty($this->class)) {
			$out .= ' '. $this->class;
		}
		$out .= '">';
		foreach( $this->content as $btn ) {
			$out .= $btn->HTML();
		}
		$out .= '</ul>';
		return $out. PHP_EOL;
	}

}
