<?php

namespace Yuma\HTML;
class Breadcrumb {
	public array $param;

	public function getId() {
		return $this->param['id'];
	}

	public function getText() {
		return $this->param['text'];
	}

	public function getTitle() {
		return $this->param['title'];
	}

	public function getLink() {
		return $this->param['link'];
	}
	public function __construct(array $param/*string $id, string $text, string $title, string $link*/) {
		$this->param = $param;
		return $param;
	}

	public function add(string $id, string $text, string $title, string $link) {
		$this->id = $id;
		$this->text = $text;
		$this->title = $title;
		$this->link = $link;
		return array(
			'id' => $this->id,
			'text' => $this->text,
			'title' => $this->title,
			'link' => $this->link,
		);
	}

	public function proper() {
?>
<ol itemscope itemtype="https://schema.org/BreadcrumbList">
   <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
      <a itemprop="item" href="https://php.fyi/articles/php-breadcrumbs">
         <span itemprop="name">Title</span>
      </a>
      <meta itemprop="position" content="1" />
   </li>
	<!<!--  and repeat -->
</ol>
<?php
		return /*static::$*/$this->link;
	}

}
