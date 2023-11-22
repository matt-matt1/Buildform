<?php
class Button {
/*	public $text;
	public $slug;
	public $class;
	public $indents;*/
	private $defaults = Array(
		'id' => '',
		'text' => '',
		'slug' => '',
		'title' => '',
		'class' => '',
		'indents' => 0,
		'wrap' => '',
	);
	public $params = Array();

	public function __construct( array $params ) {
		$this->params = array_merge( $this->defaults, $params );
		unset($this->defaults);
	/*public function __construct( string $text, string $slug, int $indents=0, bool $canDelete=false, string $class="" ) {
		$this->text = $text;
		$this->slug = $slug;
		$this->indents = $indents;
		$this->canDelete = $canDelete;
		$this->class = $class;*/
	}

	public function HTML() {
		$out = '';
		if (isset($this->params['indents']) && $this->params['indents'] /*&& is_numeric($this->indents)*/) {
			//if (is_bool($this->params['indents'] {
			//	$this->indents = 1;
			//}
			//$out .= 'this-indents = '. $this->indents;
			if (is_numeric($this->params['indents'])) {
				$out .= str_repeat( "\t", $this->params['indents'] );
			} else {
				$out .= "\t";
			}
		}
		$out .= (isset($this->params['wrap']) && !empty($this->params['wrap'])) ? '<'. htmlspecialchars($this->params['wrap']) : '<li';
		if (isset($this->params['id']) && !empty($this->params['id'])) {
			$out .= ' id="'. htmlspecialchars($this->params['id']). '"';
		}
		if (isset($this->params['title']) && !empty($this->params['title'])) {
			$out .= ' title="'. htmlspecialchars($this->params['title']). '"';
		}
		if (isset($this->params['class']) && !empty($this->params['class'])) {
			$out .= ' class="'. htmlspecialchars($this->params['class']). '"';
		}
		$out .= '>';
		if (isset($this->params['slug']) && !empty($this->params['slug'])) {
			//$out .= '<a href="'. urlencode($this->slug). '">';
			$out .= '<a href="'. $this->params['slug']. '">';
		} else {
			$out .= '<span>';
		}
		$out .= htmlspecialchars($this->params['text']);
		if (isset($this->params['slug']) && !empty($this->params['slug'])) {
			$out .= '</a>';
		} else {
			$out .= '</span>';
		}
		$out .= '</li>';
		return $out;
	}

}
