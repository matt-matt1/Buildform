<?PHP
  namespace Chirp;

  class CaptchaNonceNoCookie extends CaptchaNoCookie
  {

    // Original PHP code by Chirp Internet: www.chirpinternet.eu
    // Please acknowledge use of this code by including this header.

    private static function tempfile($crypted)
    {
      return sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, "_", $crypted);
    }

    public function display()
    {
      touch(self::tempfile($this->crypted));
      return parent::display();
    }

    public static function validate($crypted, $user_input)
    {
      if(file_exists(self::tempfile($crypted))) {
        if(parent::validate($crypted, $user_input)) {
          unlink(self::tempfile($crypted));
          return TRUE;
        } else {
          // validation failed
        }
      } else {
        // code already used or expired
      }
      return FALSE;
    }

  }


/*
  $myCaptcha = new \Chirp\CaptchaNonceNoCookie();
?>

<form method="POST" action="...">
<input type="hidden" name="crypted" value="<?= $myCaptcha->crypted(); ?>">
<p><img src="<?= $myCaptcha->display(); ?>" alt=""></p>
<p>CAPTCHA: <input type="text" required pattern="\d{<?= $myCaptcha->digits; ?>}" name="captcha"><br>
<input type="submit"></p>
</form>

PHP
  if(!\Chirp\CaptchaNonceNoCookie::validate($_POST['crypted'], $_POST['captcha'])) {
    die("Sorry, the CAPTCHA code you entered was not correct!");
  }

  // CAPTCHA passed validation
?>
*/
