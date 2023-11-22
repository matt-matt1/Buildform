<?php

namespace Yuma;

class MyCookie
{
	public function getMachineID()
	{
		if (file_exists('/etc/machine-id')) {
			return trim(shell_exec('cat /etc/machine-id 2>/dev/null'));
		} else {
			return false;
		}
	}

	public function getHTTPinfo()
	{
		if (function_exists('getServerValue')) {
			if (empty(getServerValue('REMOTE_ADDR')) {
				$ip = getHeader('X-Forwarded-For');//getServerValue('REMOTE_ADDR');
			} else {
				$ip = getServerValue('REMOTE_ADDR');
			}
			return md5(getServerValue('HTTP_USER_AGENT') . $ip);
		} else {
			if (empty($_SERVER['REMOTE_ADDR'])) {
				$ip = get_header('X-Forwarded-For');
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return md5($_SERVER['HTTP_USER_AGENT'] . $ip);
		}
	}

	public function getUnique()
	{
		//$fingerprint = [php_uname(), disk_total_space('.'), filectime('/'), phpversion()];
		$code = [php_uname(), date('Y-M-d h:m:s')];
		return hash('sha256', json_encode($code));
	}

}
