<?php
/*
    Encryption class
*/
class Encryption{

    private $key;
    private $cipher;

    function __construct() {
        $env = new Env();
        $this->key = $env->APP_ENC_KEY;
    }

    function encrypt($text) {
        $length   = 8;
        $cstrong  = true;
        $cipher   = 'aes-128-cbc';
    
      if (in_array($cipher, openssl_get_cipher_methods())){
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt(
          $text, $cipher, $this->key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
        $encodedText = base64_encode( $iv.$hmac.$ciphertext_raw );
      }
    
      return $encodedText;

    }

    function decrypt($hash) {
        $c = base64_decode($hash);
        $cipher   = 'aes-128-cbc';
    
      if (in_array($cipher, openssl_get_cipher_methods())){
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ivlenSha2len = $ivlen+$sha2len;
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $plainText = openssl_decrypt(
          $ciphertext_raw, $cipher, $this->key, $options=OPENSSL_RAW_DATA, $iv);
    }
    
      return $plainText;

    }

}
?>