<?PHP
  namespace Chirp;

  class CaptchaNoCookie
  {

    // Original PHP code by Chirp Internet: www.chirpinternet.eu
    // Please acknowledge use of this code by including this header.

    protected static $encryption_key;

    protected $font = "didot/GFSDidotBold.otf";
    protected $fontsize = 28;
    protected $code = "";
    protected $crypted = "";
    public $digits = 6;

    public function __construct()
    {
      self::$encryption_key = gethostname() . __CLASS__;

      // generate CAPTCHA code
      for($i=0; $i < $this->digits; $i++) {
        $this->code .= rand(0, 9);
      }
    }

    public function crypted()
    {
      if(!$this->crypted) {
        $cryptor = new Cryptor(self::$encryption_key);
        $this->crypted = $cryptor->encrypt($this->code);
      }
      return $this->crypted;
    }

    public function display()
    {
      // calculate required canvas size
      $box = imagettfbbox($this->fontsize, 0, $this->font, "88888");
      $boxwidth = abs(round($box[4] - $box[0]) * 1.2);
      $boxheight = abs(round($box[5] - $box[1]));
      $width = round($boxwidth * 1.2);
      $height = round($boxheight * 1.4);

      // create image canvas
      $image = @imagecreatetruecolor($width, $height) or die("Cannot Initialize new GD image stream");

      // background fill
      $background = imagecolorallocate($image, 0x66, 0xCC, 0xFF);
      imagefill($image, 0, 0, $background);

      // allocate line colours
      $linecolor = imagecolorallocate($image, 0x33, 0x99, 0xCC);
      $textcolor1 = imagecolorallocate($image, 0x00, 0x00, 0x00);
      $textcolor2 = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);

      // draw random ilnes
      for($i=0; $i < 8; $i++) {
        imagesetthickness($image, rand(1, 3));
        imageline($image, rand(0, $width), 0, rand(0, $width), $height, $linecolor);
      }

      // paint digits on canvas
      for($i=0; $i < $this->digits; $i++) {
        $x = ceil($i * $boxwidth / $this->digits);
        $angle = rand(-20, 20);
        $color = (rand() % 2) ? $textcolor1 : $textcolor2;
        $xpos = round($width/10 + $x);
        $shim = ($height - $boxheight)/2; // don't ask
        $ypos = rand($boxheight - $shim, $boxheight + $shim);
        imagettftext($image, $this->fontsize, $angle, $xpos, $ypos, $color, $this->font, $this->code[$i]);
      }

      // return image as Data URI
      ob_start();
      imagepng($image);
      $image_data = "data:image/png;base64," . base64_encode(ob_get_clean());
      imagedestroy($image);
      return $image_data;
    }

    public static function validate($crypted, $user_input)
    {
      $cryptor = new Cryptor(self::$encryption_key);
      $decrypted_token = $cryptor->decrypt($crypted);
      return $user_input == $decrypted_token;
    }

}
