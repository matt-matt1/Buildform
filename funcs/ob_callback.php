<?php
namespace Yuma;
/**
 * Callback for ob_start to reset the directory
 */
function reset_pwd()
{
	chdir( dirname(getServerValue('SCRIPT_FILENAME')) );
}
/**
 * Simple callback to allow phpsession global variables like, $_GET, nice
 * @author butch at enterpol dot pl : https://www.php.net/manual/en/function.ob-start.php
 *
 * eg. ob_start("callback");
 *
 * session_start();
 */
function callback($buffer)
{
  $buffer = str_replace("&PHPSESSID", "&amp;PHPSESSID", $buffer);
  return $buffer;
}
