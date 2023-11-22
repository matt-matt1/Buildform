<?php
namespace Yuma\HTML;
/**
 * Manages the data to be displayed for pages
 *
 * if more that one page of data (total data records > records-per-page)
 * a start, previous, page numbers, next and end button are shown
 * the current page number is highlighted (as well as the number of rows-per-page button)
 * the start button is disabled if the current page($this->page) is not the first page
 * the previous button is disabled if the current page is the first page
 * the next button is disabled if the current page is the last page
 * the end button is disabled if the current page is not the last page
 */
class Pagination
{
	const OVERRIDE_maxDisplayPages = 10;
	private $QS_pagenum = 'pagenum';
	private $QS_numrows = 'rows';
	private $params;
	private $defaults = array(
		'id' => 'mytable',
		'first' => '[<<]',
		'previous' => '<',
		'rpp' => 25,
		'recordsPerPage' => array(
			10, 25, 50,
		),
		'next' => '>',
		'last' => '[>>]',
		'maxDisplayPages' => 6,
		'hideFor1Page' => false,
	);
	private $page;
	private $rpp;
	private $allData, $numPages, $maxDisplayPages, $groupLeader;

	/**
	 * Initialise class variables
	 */
	public function __construct(array $params=null, $data) {
		$this->params = array_merge($this->defaults, (array)$params);
		unset($this->defaults);
		if (is_array($data)) {
			$this->allData = $data;
		}
		if (!$this->page) {
			$this->page = 0;
		}
		if (!$this->rpp) {
			$this->rpp = $this->params['rpp'];
		}
		if (!isset($this->params['id'])) {
			$this->params['id'] = '';
		}
		$this->QS_pagenum = $this->params['id']. '_'. $this->QS_pagenum;
		$this->QS_numrows = $this->params['id']. '_'. $this->QS_numrows;
	}

	/**
	 * Returns the data for the given (or current) page
	 */
	public function getDataForPage(int $page=null)
	{
		if (isset($_GET[$this->QS_pagenum])) {
			$this->page = intval($_GET[$this->QS_pagenum]);
		}
		if (isset($_GET[$this->QS_numrows])) {
			$this->rpp = intval($_GET[$this->QS_numrows]);
		}
		if (!isset($page)) {
			$page = $this->page;
		}
		if (is_array($this->allData)) {
//			echo 'getDataForPage: page='. $page. ' data from '. ($page*$this->rpp). ' to '. (($page+1)*$this->rpp). ' of '. count($this->allData). '.'. "<br>\n";
			return array_slice($this->allData, $page * $this->rpp, ($page+1) * $this->rpp);
		}
		return null;
	}

	/**
	 * Displays the buttons to select amount of recorda for each page
	 */
	private function displayRecordsPerPage()
	{
		ob_start();
?><div class="recordsPerPage">
	<input type="hidden" name="<?=$this->QS_numrows?>" value="<?=$this->rpp?>">
<?php
		foreach ($this->params['recordsPerPage'] as $num) {
			if (is_numeric($num)) {
				echo '<label><button type="submit" class="no-button recordsPerPage';
				if ($this->rpp == $num) {
					echo ' active';
				}
				echo '" name="'. $this->QS_numrows. '" value="'. $num. '"';
				if ($this->rpp == $num || ((count($this->allData) - ($this->page*$this->rpp)) < $num) && $this->page) {
					echo ' disabled';
				}
				echo '>'. $num. '</button></label>';
			}
		}
?></div>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	/**
	 * Displays the navigation buttons for the pagination bar
	 */
	private function navigation()
	{
		ob_start();
?>	<input type="hidden" name="<?=$this->QS_pagenum?>" value="<?=$this->page?>"><?php
?>	<label for="page_1"><button type="submit" class="no-button first" name="<?=$this->QS_pagenum?>" value="0"<?php
		if (!$this->page) {
			echo ' disabled';
		}
?>><?php
		echo $this->params['first'];
?></button></label>

	<label for="page_<?php
		echo max(0, $this->page - 1);
?>"><button type="submit" class="no-button previous" name="<?=$this->QS_pagenum?>" value="<?php
		echo max(0, $this->page - 1);
?>"<?php
		if ($this->page == 0) {
			echo ' disabled';
		}
?>><?php
		echo $this->params['previous'];
?></button></label>

<?php
		for ($i = $this->groupLeader; $i < min($this->maxDisplayPages, $this->numPages); $i++) {
			echo '<label for="page_'. $i. '">';
//			echo '<span>';
			echo '<button type="submit"';
			echo ' class="no-button pagenum';
			if ($this->page == $i) {
				echo ' active';
			}
			echo '" name="'. $this->QS_pagenum. '" value="'. $i. '"';
			if ($this->page == $i) {
				echo ' disabled';
			}
			echo '>'. ($i+1);
			echo '</button>';
//			echo '</span>';
			echo '</label>';
		}

?>	<label for="page_<?php
		echo min($this->numPages-1, ($this->page+1));
?>"><button type="submit" class="no-button next" name="<?=$this->QS_pagenum?>" value="<?php
		echo min($this->numPages-1, ($this->page+1));
?>"<?php
		if ($this->page >= $this->numPages-1 || $this->rpp > count($this->allData)) {
			echo ' disabled';
		}
?>><?php
		echo $this->params['next'];
?></button></label>

	<label for="page_<?php
		echo $this->numPages-1;
?>"><button type="submit" class="no-button last" name="<?=$this->QS_pagenum?>" value="<?php
		echo $this->numPages-1;
?>"<?php
		if ($this->page != 0 || $this->rpp > count($this->allData)) {
			echo ' disabled';
		}
?>><?php
		echo $this->params['last'];
?></button></label>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	/**
	 * Displays the pagination bar
	 */
	public function render()
	{
		ob_start();
		if (isset($this->params['hideFor1Page']) && $this->params['hideFor1Page'] && $this->numPages == 1) {
			return 'hideFor1Page';
		}
		$this->numPages = max(1, ceil(count($this->allData) / $this->rpp));
		$this->maxDisplayPages = isset($this->params['maxDisplayPages']) ? intval($this->params['maxDisplayPages']) : OVERRIDE_maxDisplayPages;
		$this->groupLeader = max(0, min($this->numPages - $this->maxDisplayPages, intval($this->page) - floor($this->maxDisplayPages / 2)));
?><div class="pagination sticky">
<?php
			echo $this->navigation();
			if (isset($this->params['recordsPerPage']) && is_array($this->params['recordsPerPage'])) {
				echo $this->displayRecordsPerPage();
			}
?></div>
<?php
//		echo 'Pagination:<pre>'. print_r($this, true). '</pre>'. "\n";		
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

}
