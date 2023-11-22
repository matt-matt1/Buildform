<?php
namespace Yuma;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
$pre = 'PHPMailer:: ';
$base = 'class/PHPMailer/PHPMailer/';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//require $base. 'src/Exception.php';
//require $base. 'src/PHPMailer.php';
//require $base. 'src/SMTP.php';
//$code = strtolower(do_mbstr('substr', Lang::getInstance()->getCode(), 0, 2));
//error_log( $pre. $code );
$mail = new PHPMailer(true);
try {
	//Server settings
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	$mail->isSMTP();                                            //Send using SMTP
	$mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->Username   = 'user@example.com';                     //SMTP username
	$mail->Password   = 'secret';                               //SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
	$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

	//Recipients
	$mail->setFrom('from@example.com', 'Mailer');
	$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
	$mail->addAddress('ellen@example.com');               //Name is optional
	$mail->addReplyTo('info@example.com', 'Information');
	$mail->addCC('cc@example.com');
	$mail->addBCC('bcc@example.com');

	//Attachments
//	$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//	$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

	//Content
	$mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = 'Here is the subject';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	$langDir = 'other/PHPMailer.all/language/';
	$lng = new Lang;
	$code = strtolower(do_mbstr('substr', $lng->getLanguage(), 0, 2));
	if ($code !== 'en') {
		$langArr = array();
		//foreach ( glob_recursive( $base. 'language/', "*.php") as $filename ) {
		foreach ( glob_recursive( $langDir, "*.php") as $filename ) {
			$name = do_mbstr('substr', basename($filename, '.php'), 15);
			$langArr[$name] = $filename;
			$log = 'mail using: '. $name;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
		}
		//if (in_array($code, array_keys($langArr))) {
		if (array_key_exists($code, $langArr)) {
			$mail->setLanguage($code, $langArr[$name]);
		}
	}
	
	//$mail->send();
	//echo 'Message has been sent';
/*	$log = "Message sent";// to: {$mail->ErrorInfo}";
	$trace = debug_backtrace();
	$log .= ' (in ' . $trace[1]['file'] .
		':' . $trace[1]['line']. ')';
	(is_callable(array('Error', 'log'))) &&
		Error::log( $pre. $log ) ||
		error_log( $pre. $log );	*/
/*					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}*/
} catch (Exception $e) {
	//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	$log = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	$trace = debug_backtrace();
	$log .= ' (' . $trace[1]['file'] . ':' . $trace[1]['line']. ')';
	try {
		$logger = new Logger;
		$logger->log($pre. $log);
	} catch (Exception $e) {
		error_log ($pre. $log);
	}
/*	(is_callable(array('Error', 'log'))) &&
		Error::log( $pre. $log ) ||
		error_log( $pre. $log );*/
}
