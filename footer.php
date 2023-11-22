<?php
namespace Yuma;
use Yuma\HTML\Scripts;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	$scp = new Scripts();
	$feet = $scp->getInFoot();
	foreach ($feet as $f)
	{
		echo "\t{$f}\n";
	}
?>
</body>
</html>
