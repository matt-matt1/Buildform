<?php
namespace Yuma\HTML;
/**
 * Dynamic tabbed interface
 */
class TabBar
{
	public $defaults = array(
		'data' => array(
			array(
				'heading' => 'Title',
				'content' => 'This is some text',
			),
			array(
				'heading' => 'Title2',
				'content' => 'This is some other text',
			),
		),
	);
	public $params;
	public $current = null;

	public function __construct(array $params=null)
	{
		$this->params = array_merge($this->defaults, (array)$params);
		unset($this->defaults);
		if (isset($_GET['tab']) && is_numeric($_GET['tab'])) {
			$this->current = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_NUMBER_INT);
		}
	}

	/**
	 * Sets the class variable 'current' (that marks the active tab) to either:
	 * the speciofied tab number, if a number is given,
	 * otherwise, if a non-numeric value is given, the specified id of the tab.
	 * Returns the tab number or
	 * false if the given number exceeds the number of tabs or, if a non-numeric value is given and the id is unknown
	 */
	public function makeActive($num_or_id)
	{
//		throw new Exception('Helko');
//		echo '<p style="position:absolute;left:50%;top:1%">makeActive: '. $num_or_id. "</p><br>\n";
		if (is_numeric($num_or_id)) {
//			echo 'makeActive numeric: '. $num_or_id. "<br>\n";
			if (intval($num_or_id) >= count($this->params['data'])) {
				return false;
			}
			$this->current = $num_or_id;
			return $num_or_id;
		} else {
//			echo 'makeActive non-numeric: '. strtolower($num_or_id). "<br>\n";
			$tab_num = 1;
			foreach ($this->params['data'] as $tab) {
				$id = (isset($tab['id']) ? strtolower($tab['id']) : isset($tab['heading'])) ? strtolower($tab['heading']) : 'id-tab_'. $tab_num;
//				echo 'testing "'. $id. '" == "'. strtolower($num_or_id). '"'. "<br>\n";
				//if ($num_or_id == ($id. '-tab')) {
				if (strtolower($num_or_id) == $id || strtolower($num_or_id) == ($id. '-tab')) {
//					echo '-found'. "\n";
					$this->current = $tab_num;
					return $tab_num;
				}
				$tab_num++;
			}
			return false;
		}
	}

	public function render()
	{
		$tab_num = 1;
		ob_start();
?><div class="tabs-group">
<ul class="tabs" role="tablist">
<?php
		foreach ($this->params['data'] as $tab) {
?>	<li>
		<input type="radio" name="tab-control" id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' checked' : ''?> />
        <label for="tab<?=$tab_num?>" 
	        role="tab" 
			aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" 
	        aria-controls="panel<?=$tab_num?>" 
			tabindex="0"><?=$tab['heading']?></label>
		<div class="tab-slider"><div class="indicator"></div></div>
		<div id="tab-content<?=$tab_num?>"
			class="tab-content"
			role="tabpanel"
			aria-labelledby="<?=$tab['heading']?>"
			aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
<?php
			echo $tab['content'];
?>		</div>
	</li><?php
			$tab_num++;
		}
?></ul>
</div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render3()
	{
		$tab_num = 1;
		ob_start();
?><div class="tabs">
<?php
		foreach ($this->params['data'] as $tab) {
?>	<input type="radio"
		name="tab-control"
		id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' checked' : ''?> />
<?php
			$tab_num++;
		}
?>	<ul>
<?php
		foreach ($this->params['data'] as $tab) {
?>
		<li id="tab-title<?=$tab_num?>" title="<?=$tab['heading']?>">
			<label for="tab<?=$tab_num?>"
				role="tab" 
				aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" 
				aria-controls="panel<?=$tab_num?>" 
				tabindex="0"><span><?=$tab['heading']?></span></label>
		</li>
<?php
			$tab_num++;
		}
?>	</ul>
	<div class="slider"><div class="indicator"></div></div>
	<div class="content">
<?php
		foreach ($this->params['data'] as $tab) {
?>
		<section id="tab-content<?=$tab_num?>"
             class="tab-content"
             role="tabpanel"
             aria-labelledby="<?=$tab['heading']?>"
			 aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
			<h2><?=$tab['heading']?></h2>
<?php
			echo $tab['content'];
?>		</section>
<?php
			$tab_num++;
		}
?>	</div>
</div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function renderUNK()
	{
		$tab_num = 1;
		ob_start();
?><div class="tabs">
<ul class="tabs" role="tablist">
<?php
		foreach ($this->params['data'] as $tab) {
?>	<li>
		<input type="radio" name="tab-control" id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' checked' : ''?> />
        <label for="tab<?=$tab_num?>" 
	        role="tab" 
			aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" 
	        aria-controls="panel<?=$tab_num?>" 
			tabindex="0"><?=$tab['heading']?></label>
		<div class="tab-slider"><div class="indicator"></div></div>
		<div id="tab-content<?=$tab_num?>"
			class="tab-content"
			role="tabpanel"
			aria-labelledby="<?=$tab['heading']?>"
			aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
<?php
			echo $tab['content'];
?>		</div>
	</li><?php
			$tab_num++;
		}
?></ul>
</div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render4()
	{
		$tab_num = 1;
		ob_start();
?><div class="warpper">
<?php
		foreach ($this->params['data'] as $tab) {
?>	<input type="radio" name="tabs-group" id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' checked' : ''?> />
	<label for="tab<?=$tab_num?>"
		role="tab" class="tab" 
		aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" 
		aria-controls="panel<?=$tab_num?>" 
		tabindex="0"><?=$tab['heading']?></label>
<?php
		}
?></div>
<div class="panels">
<?php
		foreach ($this->params['data'] as $tab) {
?>
		<div id="tab-content<?=$tab_num?>"
             class="tab-content"
             role="tabpanel"
             aria-labelledby="<?=$tab['heading']?>"
			 aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
<?php
			echo $tab['content'];
?>		</div>
<?php
			$tab_num++;
		}
?></div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render_5()
	{
		$tab_num = 1;
		ob_start();
?><div class="tabs">
<?php
		foreach ($this->params['data'] as $tab) {
?>	<input type="radio"
		name="tab-control"
		id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' checked' : ''?> />
	<label for="tab<?=$tab_num?>"
		role="tab" 
		aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" 
		aria-controls="panel<?=$tab_num?>" 
		tabindex="0"><span><?=$tab['heading']?></span></label>
<?php
			$tab_num++;
		}
?>
	<div class="slider"><div class="indicator"></div></div>
	<!--div class="content"-->
<?php
		foreach ($this->params['data'] as $tab) {
?>
		<div id="tab-content<?=$tab_num?>"
             class="tab-content"
             role="tabpanel"
             aria-labelledby="<?=$tab['heading']?>"
			 aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
			<!--h2><?=$tab['heading']?></h2-->
<?php
			echo $tab['content'];
?>		</div>
<?php
			$tab_num++;
		}
?>	<!--/div-->
</div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render5()
	{
		$tab_num = 1;
		ob_start();
?><div class="tabs">
	<ul>
<?php
		foreach ($this->params['data'] as $tab) {
?>		<li>
			<input type="radio"
				name="tab-control"
				id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' checked' : ''?> />
			<label for="tab<?=$tab_num?>"
				role="tab" 
				aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" 
				aria-controls="panel<?=$tab_num?>" 
				tabindex="0"><span><?=isset($tab['heading']) ? $tab['heading'] : 'Title'?></span></label>
			<div class="slider"><div class="indicator"></div></div>
			<div id="tab-content<?=$tab_num?>"
		         class="tab-content"
		         role="tabpanel"
		         aria-labelledby="<?=isset($tab['heading']) ? $tab['heading'] : 'Title'?>"
				 aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
<?php
			echo isset($tab['content']) ? $tab['content'] : 'No content';
?>			</div>
		</li>
<?php
			$tab_num++;
		}
?>	</ul>
</div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render6()
	{
		$tab_num = 1;
		ob_start();
?><snap-tabs>
	<header class="scroll-snap-x">
		<nav>
<?php
		foreach ($this->params['data'] as $tab) {
?>			<a href="#tab<?=$tab_num?>"
				name="tab-control"
				role="tab"
				aria-labelledby="tab_<?=isset($tab['heading']) ? $tab['heading'] : 'Title'?>"
				data-aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>"
				id="tab<?=$tab_num?>"<?=($this->current == $tab_num) ? ' active' : ''?>>
<?=isset($tab['heading']) ? $tab['heading'] : 'Title'?></a>
<?php
			$tab_num++;
		}
?>		</nav>
		<span class="snap-indicator"></span>
	</header class="scroll-snap-x">
	<section>
<?php
		$tab_num = 1;
		foreach ($this->params['data'] as $tab) {
?>			<article type="radio"
				name="tab-control"
				id="tab<?=$tab_num?>"
				role="tabpanel"
				aria-labelledby="<?=isset($tab['heading']) ? $tab['heading'] : 'Title'?>"
				data-aria-hidden="<?=($this->current != $tab_num) ? 'true' : 'false'?>">
			<!--h2 tabindex="0"><?=isset($tab['heading']) ? $tab['heading'] : 'Title'?></h2-->
<?php
			echo isset($tab['content']) ? $tab['content'] : 'No content';
?>			</article>
<?php
			$tab_num++;
		}
?>	</section>
</snap-tabs>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render7()
	{
//		echo '<p style="position:absolute;left:50%;top:1%">render7'. "</p><br>\n";
		$wrapClass = isset($this->params['wrapClass']) ? $this->params['wrapClass'] : 'panel novisual';
		$ulClass = isset($this->params['ulClass']) ? ' '. $this->params['ulClass'] : ' xnav-justified nav-fill justify-content-center';
		$extra = isset($this->params['liExtra']) ? ' '. $this->params['liExtra'] : '';
		$tabsName = isset($this->params['tabs-name']) ? $this->params['tabs-name'] : rand();
		$ulExtra = isset($this->params['ulExtra']) ? $this->params['ulExtra'] : '';
		$tabsContentClass = isset($this->params['tabsContentClass']) ? ' '. $this->params['tabsContentClass'] : '';
		if (null == $this->current)
			$this->current = 1;
		$tab_num = 1;
		ob_start();
		if ($wrapClass) {
?>		<div class="<?=$wrapClass?>"><?php /* role="navigation"> */ ?>
<?php	}
?>			<ul id="tabs-list_<?=$tabsName?>" class="nav nav-tabs<?=$ulClass?>" role="tablist"<?=$ulExtra?>>
<?php
		foreach ($this->params['data'] as $tab) {
			$id = (isset($tab['id']) ? strtolower(str_replace(' ', '-', $tab['id'])) : isset($tab['heading'])) ? strtolower(str_replace(' ', '-', $tab['heading'])) : 'id-tab_'. $tab_num;
?>				<li class="nav-item"<?=$extra?>role="presentation">
					<button class="nav-link<?=($this->current == $tab_num) ? ' active' : ''?>" id="<?=$id?>-tab" data-bs-toggle="tab" data-bs-target="#<?=$id?>-tab-pane" type="button" role="tab" aria-controls="<?=$id?>-tab-pane" aria-selected="<?=($this->current == $tab_num) ? 'true' : 'false'?>" data-list="<?=$tabsName?>"><?=isset($tab['heading']) ? $tab['heading'] : 'Tab-'. $tab_num?></button>
				</li>
<?php		$tab_num++;
		}
?>			</ul>

			<div id="tabs-content_<?=$tabsName?>" class="tab-content<?=$tabsContentClass?>">
<?php	$tab_num = 1;
		foreach ($this->params['data'] as $tab) {
			$id = (isset($tab['id']) ? strtolower(str_replace(' ', '-', $tab['id'])) : isset($tab['heading'])) ? strtolower(str_replace(' ', '-', $tab['heading'])) : 'id-tab_'.$tab_num;
?>				<div id="<?=$id?>-tab-pane" class="tab-pane<?=isset($this->params['tabPaneClass']) ? ' '. $this->params['tabPaneClass'] : ''?><?=($this->current == $tab_num) ? (isset($this->params['tabActivePaneClass']) ? ' '. $this->params['tabActivePaneClass'] : ''). ' active' : ''?><?=isset($this->params['initialShow']) ? ' show' : ''?>" role="tabpanel" aria-labelledby="<?=$id?>-tab-pane">
<?php
			echo isset($tab['content']) ? $tab['content'] : 'No content';
?>				</div>
<?php		$tab_num++;
		}
?>			</div>
<?php	if ($wrapClass) {
?>		</div>
<?php		}
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

}
