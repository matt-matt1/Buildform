<?php

namespace Yuma;

/**
 * Encrypts and decrypts a piece of data
 */
class SSL
{
	/**
	 * Usage:
	 * 1. Generate a 32-byte string of random bytes : gen32()
	 * 2. Generate a 64-byte string of random bytes : gen64()
	 * 3. optional: use a certain cipher - default is "aes-256-cbc" : setCipher(string $cipher)
	 * 4. Securely encrypt data : secure_encrypt($data)
	 * 5. Securely decrypt data (that was previously encrypted) : secure_decrypt($data)
	 */

	/**
	 * Define the number of blocks that should be read from the source file for each chunk.
	 * For 'AES-128-CBC' each block consist of 16 bytes.
	 * So if we read 10,000 blocks we load 160kb into memory. You may adjust this value
	 * to read/write shorter or longer chunks.
	 */
	const FILE_ENCRYPTION_BLOCKS = 10000;

	public static $key1 = null;		// usually 32bytes
	public static $key2 = null;		// usually 64bytes
	public static $iv = null;		// BINARY string
	public static $cipher = "aes-256-cbc";
	public static $algorithm = 'sha3-512';
/*
	public __construct($data, $first_key, $second_key, $cipher = "aes-256-cbc")
	{
		$iv_length = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($iv_length);
		$first_encrypted = openssl_encrypt($data, $cipher, $first_key, OPENSSL_RAW_DATA, $iv);	// first_key
		$second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
		$output = base64_encode($iv.$second_encrypted.$first_encrypted);
	}
*/

	/**
	 * Sets a certain cipher
	 */
	public static function setCipher(string $cipher)
	{
		static::$cipher = $cipher;
	}

	/**
	 * Decode a given key, using base64
	 */
/*	public static function decodeKey($key)
	{
		return base64_decode($key);
	}
*/

	/**
	 * List of ciphers available for the server
	 * @return array
	 */
	public static function available()
	{
		return openssl_get_cipher_methods();
	}

	/**
	 * Returns true if given cipher is available in the openSSL list
	 * @return true|false
	 */
	public static function isAvailable($cipher)
	{
		return in_array($cipher, static::available());
	}

	/**
	 * Securely encrypt data
	 * (inspired from https://www.php.net/openssl_encrypt - omidbahrami1990 at gmail dot com)
	 * @return string
	 */
	public static function secure_encrypt($data)
	{
		if (!static::$key1) {
			static::gen32();
			//throw new Exception('[SSL::secure_encrypt] Must generate a 32-byte string before using this method (Use gen32()).');
		}
		if (!static::$key2) {
			static::gen64();
			//throw new Exception('[SSL::secure_encrypt] Must generate a 64-byte string before using this method (Use gen64()).');
		}
		$first_key = base64_decode(static::$key1);
		$second_key = base64_decode(static::$key2);
		if (!static::isAvailable(static::$cipher)) {
			throw new Exception('[SSL::secure_encrypt] Cipher is not available - recommend "aes-256-cbc".');
		}
		$method = static::$cipher;
		echo 'method='. $method. "<br>\n";
		if (!static::$iv) {
			static::$iv = static::genIV($method);
			//throw new Exception('[SSL::secure_encrypt] Must define a Initialisation Vector (Use genIV()).');
		}	
		echo 'iv='. static::$iv. "<br>\n";
		$first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, static::$iv);
		echo 'first_encrypted='. $first_encrypted. "<br>\n";
		$second_encrypted = hash_hmac(static::$algorithm, $first_encrypted, $second_key, TRUE);
		echo 'second_encrypted='. $second_encrypted. "<br>\n";
		return base64_encode(static::$iv . $second_encrypted . $first_encrypted);
	}

	/**
	 * Securely decrypt data (that was previously encrypted)
	 * (inspired from https://www.php.net/openssl_encrypt - omidbahrami1990 at gmail dot com)
	 * @return string|false
	 */
	public static function secure_decrypt($data)
	{
		if (!static::$key1 || strlen(static::$key1) != 32) {
			static::gen32();
			//throw new Exception('[SSL::secure_decrypt] Must generate a 32-byte string before using this method (Use gen32()).');
		}
		if (!static::$key2 || strlen(static::$key2) != 64) {
			static::gen64();
			//throw new Exception('[SSL::secure_decrypt] Must generate a 64-byte string before using this method (Use gen64()).');
		}
		$first_key = base64_decode(static::$key1);
		$second_key = base64_decode(static::$key2);
//		echo '$first_key='. $first_key. "<br>\n";
//		echo 'second_key='. $second_key. "<br>\n";
		$mix = base64_decode($data);
//		echo 'mix='. $mix. "<br>\n";
		if (!static::isAvailable(static::$cipher)) {
			throw new Exception('[SSL::secure_decrypt] Cipher is not available - recommend "aes-256-cbc".');
		}
		$method = static::$cipher;
		echo 'method='. $method. "<br>\n";
/*		if (!static::$iv) {
			static::$iv = static::genIV($method);
			//throw new Exception('[SSL::secure_encrypt] Must define a Initialisation Vector (Use genIV()).');
		}*/	
		$length = static::getIVLength($method);
		echo 'length='. $length. "<br>\n";
		$iv = substr($mix, 0, $length);
		echo 'iv='. $iv. "<br>\n";
		$second_encrypted = substr($mix, $length, 64);//strlen(static::$key2));
		echo 'second_encrypted='. $second_encrypted. "<br>\n";
		$first_encrypted = substr($mix, $length+64);//strlen(static::$key2));
		echo 'first_encrypted='. $first_encrypted. "<br>\n";
		$out = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
		echo 'out='. $out. "<br>\n";
		$second_encrypted_new = hash_hmac(static::$algorithm, $first_encrypted, $second_key, TRUE);
		echo 'second_encrypted_new='. $second_encrypted_new. "<br>\n";
		if (hash_equals($second_encrypted, $second_encrypted_new))
			return $out;
		echo 'decryption failed: '. $out. "<br>\n";
		return false;
	}

	/**
	 * Returns the byte length of a specific cipher
	 * @return integer
	 */
	public static function getIVLength($cipher)
	{
		return openssl_cipher_iv_length($cipher);
	}

	/**
	 * Returns an IV given a cipher method
	 * @return string
	 */
	public static function genIV($cipher)
	{
//		return openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
		//$iv_length = openssl_cipher_iv_length($cipher);
		$iv_length = static::getIVLength($cipher);
		return openssl_random_pseudo_bytes($iv_length);
	}

	/**
	 * Generate a random string containing the given number of bytes
	 * @return string base64
	 */
	private static function generate($bytes)
	{
		return base64_encode(openssl_random_pseudo_bytes($bytes));
	}

	/**
	 * Generates a 32-byte string of random bytes, and store as class variable
	 * @return string
	 */
	public static function gen32()
	{
		static::$key1 = static::generate(32);
		return static::$key1;
	}

	/**
	 * Generates a 64-byte string of random bytes, and store as class variable
	 * @return string
	 */
	public static function gen64()
	{
		static::$key2 = static::generate(64);
		return static::$key2;
	}


/*
$secret = "MySecRet@123";
$cipherjson = encrypt("Hello world!\n", $secret);
echo decrypt($cipherjson, $secret);
 */
	/**
	 * Returns an encryped string from given data
	 * @return string
	 */
	//public static function encrypt($plaintext, $key, $cipher = static::$cipher) {
	public static function encrypt(string $plaintext, string $key, string $cipher = "aes-256-gcm") {
	    if (!in_array($cipher, openssl_get_cipher_methods())) {
	        return false;
	    }
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
	    $tag = null;
	    $ciphertext = openssl_encrypt(
	        gzcompress($plaintext),
	        $cipher,
	        base64_decode($key),
	        $options = 0,
	        $iv,
	        $tag,
	    );
	    $cipherjson = json_encode(
	        array(
	            "ciphertext" => base64_encode($ciphertext),
	            "cipher" => $cipher,
	            "iv" => base64_encode($iv),
	            "tag" => base64_encode($tag),
	        )
		);
		return base64_encode($cipherjson);
	}

	/**
	 * Returns the decrypted data
	 * @return string
	 */
	public static function decrypt(string $data, string $key) {
		try {
			$cipherjson = base64_decode($data);
			//echo 'cipherjson: '. $cipherjson. "<br>\n";
	        $json = json_decode($cipherjson, true, 2, JSON_THROW_ON_ERROR);
			//echo 'json: '. $json. "<br>\n";
	    } catch (Exception $e) {
	        return false;
	    }
	    return gzuncompress(
	        openssl_decrypt(
	            base64_decode($json['ciphertext']),
	            $json['cipher'],
	            base64_decode($key),
	            $options = 0,
	            base64_decode($json['iv']),
	            base64_decode($json['tag'])
	        )
	    );
	}

//@Untested
	/**
	 * Encrypt the passed file and saves the result in a new file with ".enc" as suffix.
	 *
	 * @param string $source Path to file that should be encrypted
	 * @param string $key    The key used for the encryption
	 * @param string $dest   File name where the encryped file should be written to.
	 * @return string|false  Returns the file name that has been created or FALSE if an error occured
	 */
	//public static function encryptFile($source, $key, $dest)
	public static function encryptFile($source, $dest)
	{
//	    $key = substr(sha1($key, true), 0, 16);		// for aes-128
//	    $iv = openssl_random_pseudo_bytes(16);
	
		if (!static::$key1) {
			static::gen32();
			//throw new Exception('[SSL::secure_encrypt] Must generate a 32-byte string before using this method (Use gen32()).');
		}
		if (!static::$key2) {
			static::gen64();
			//throw new Exception('[SSL::secure_encrypt] Must generate a 64-byte string before using this method (Use gen64()).');
		}
		$first_key = base64_decode(static::$key1);
		$second_key = base64_decode(static::$key2);
		if (!static::isAvailable(static::$cipher)) {
			throw new Exception('[SSL::secure_encrypt] Cipher is not available - recommend "aes-256-cbc".');
		}
		$method = static::$cipher;
		$iv = static::genIV($method);

		$error = false;
	    if ($fpOut = fopen($dest, 'w')) {
	        // Put the initialzation vector to the beginning of the file
	        fwrite($fpOut, $iv);
	        if ($fpIn = fopen($source, 'rb')) {
	            while (!feof($fpIn)) {
	                //$plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
	                $plaintext = fread($fpIn, static::getIVLength(static::$cipher) * static::FILE_ENCRYPTION_BLOCKS);
	                //$ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
	    //            $ciphertext = openssl_encrypt($plaintext, $cipher, static::$key1, OPENSSL_RAW_DATA, $iv);
		$first_encrypted = openssl_encrypt($plaintext, static::$cipher, static::$key1, OPENSSL_RAW_DATA, $iv);
		$second_encrypted = hash_hmac(static::$algorithm, $first_encrypted, static::$key2, TRUE);
		$ciphertext = base64_encode($iv . $second_encrypted . $first_encrypted);
	                // Use the first 16 bytes of the ciphertext as the next initialization vector
	                //$iv = substr($ciphertext, 0, 16);
	                //$iv = substr($ciphertext, 0, 16);
	                $iv = substr($ciphertext, 0, static::getIVLength(static::$cipher));
	                fwrite($fpOut, $ciphertext);
	            }
	            fclose($fpIn);
	        } else {
	            $error = true;
	        }
	        fclose($fpOut);
	    } else {
	        $error = true;
	    }
	
	    return $error ? false : $dest;
	}

//@Untested
	/**
	 * Dencrypt the passed file and saves the result in a new file, removing the
	 * last 4 characters from file name.
	 *
	 * @param string $source Path to file that should be decrypted
	 * @param string $key    The key used for the decryption (must be the same as for encryption)
	 * @param string $dest   File name where the decryped file should be written to.
	 * @return string|false  Returns the file name that has been created or FALSE if an error occured
	 */
	function decryptFile($source, $key, $dest)
	{
	    //$key = substr(sha1($key, true), 0, 16);
	    $key = substr(sha1($key, true), 0, static::getIVLength(static::$cipher));
	
	    $error = false;
	    if ($fpOut = fopen($dest, 'w')) {
	        if ($fpIn = fopen($source, 'rb')) {
	            // Get the initialzation vector from the beginning of the file
	            //$iv = fread($fpIn, 16);
	            //$iv = fread($fpIn, 16);
	            $iv = fread($fpIn, static::getIVLength(static::$cipher));
	            while (!feof($fpIn)) {
	                // we have to read one block more for decrypting than for encrypting
	                //$ciphertext = fread($fpIn, 16 * (FILE_ENCRYPTION_BLOCKS + 1));
	                $ciphertext = fread($fpIn, static::getIVLength(static::$cipher) * (static::FILE_ENCRYPTION_BLOCKS + 1));
	                //$plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
		$length = static::getIVLength(static::$cipher);
		$second_encrypted = substr($ciphertext, $length, strlen(static::$key2));
		$first_encrypted = substr($ciphertext, $length+strlen(static::$key2));
	                $plaintext = openssl_decrypt($ciphertext, static::$cipher, static::$key1, OPENSSL_RAW_DATA, $iv);
	                // Use the first 16 bytes of the ciphertext as the next initialization vector
	                //$iv = substr($ciphertext, 0, 16);
	                $iv = substr($ciphertext, 0, static::getIVLength(static::$cipher));
		$out = openssl_decrypt($first_encrypted, static::$cipher, static::$key1, OPENSSL_RAW_DATA, $iv);
		$second_encrypted_new = hash_hmac(static::$algorithm, $first_encrypted, static::$key2, TRUE);
		if (hash_equals($second_encrypted, $second_encrypted_new))
			return $out;
		return false;
	                fwrite($fpOut, $plaintext);
	            }
	            fclose($fpIn);
	        } else {
	            $error = true;
	        }
	        fclose($fpOut);
	    } else {
	        $error = true;
	    }
	
	    return $error ? false : $dest;
	}
/*
 * NOTE:
 * $string = 'It works ? Or not it works ?';
$pass = '1234567812345678'; ($iv = "1234567812345678";)
	$method = 'aes128';
	* IV and Key parameteres passed to openssl command line must be in hex representation of string.
	*
 * openssl enc -aes-128-cbc -d -in file.encrypted -nosalt -nopad -K 31323334353637383132333435363738 -iv 31323334353637383132333435363738
 */
}

