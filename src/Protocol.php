<?php

namespace Wapi\Protocol;


/**
 * Provides the React Client.
 */
class Protocol {
  
  static function decode($json_string) {
    return json_decode($json_string, TRUE);
  }
  
  static function encode($body) {
    return json_encode($body);
  }
  
  static function buildMessage($secret, $method, $data, $clock_offset = 0) {
    $time = microtime(TRUE) + $clock_offset;
    $message_id = static::randomBytesBase64();
    $body = [
      'message_id' => $message_id,
      'method' => $method,
      'timestamp' => $time,
      'data' => $data,
    ];
    $body['check'] = static::sign($secret, static::encode($body));
    return $body;
  }
  
  static function sign($secret, $string) {
    return base64_encode(hash("sha256",  $secret . $string, TRUE));
  }
  
  static function verifyClock(array $body, $threshold = 15, $offset = 0) {
    if(isset($body['timestamp'])) {
      $time = $body['timestamp'];
      return abs(microtime(TRUE) - $time - $offset) < $threshold;
    }
  
    return FALSE;
  }
  
  static function verifyMessage($secret, array $body) {
    if(isset($body['check'])) {
      $check = $body['check'];
      unset($body['check']);
      return (static::sign($secret, static::encode($body)) === $check);
    }
    
    return FALSE;
  }
  
  /**
   * Returns a URL-safe, base64 encoded string of highly randomized bytes.
   *
   * @param $count
   *   The number of random bytes to fetch and base64 encode.
   *
   * @return string
   *   The base64 encoded result will have a length of up to 4 * $count.
   */
  static function randomBytesBase64($count = 32) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(static::randomBytes($count)));
  }
  
  /**
   * Returns a string of highly randomized bytes (over the full 8-bit range).
   *
   * This function is better than simply calling mt_rand() or any other built-in
   * PHP function because it can return a long string of bytes (compared to < 4
   * bytes normally from mt_rand()) and uses the best available pseudo-random
   * source.
   *
   * In PHP 7 and up, this uses the built-in PHP function random_bytes().
   * In older PHP versions, this uses the random_bytes() function provided by
   * the random_compat library, or the fallback hash-based generator from Drupal
   * 7.x.
   *
   * @param int $count
   *   The number of characters (bytes) to return in the string.
   *
   * @return string
   *   A randomly generated string.
   */
  static function randomBytes($count) {
    try {
      return random_bytes($count);
    }
    catch (\Exception $e) {
      // $random_state does not use drupal_static as it stores random bytes.
      static $random_state, $bytes;
      // If the compatibility library fails, this simple hash-based PRNG will
      // generate a good set of pseudo-random bytes on any system.
      // Note that it may be important that our $random_state is passed
      // through hash() prior to being rolled into $output, that the two hash()
      // invocations are different, and that the extra input into the first one
      // - the microtime() - is prepended rather than appended. This is to avoid
      // directly leaking $random_state via the $output stream, which could
      // allow for trivial prediction of further "random" numbers.
      if (strlen($bytes) < $count) {
        // Initialize on the first call. The $_SERVER variable includes user and
        // system-specific information that varies a little with each page.
        if (!isset($random_state)) {
          $random_state = print_r($_SERVER, TRUE);
          if (function_exists('getmypid')) {
            // Further initialize with the somewhat random PHP process ID.
            $random_state .= getmypid();
          }
          $bytes = '';
          // Ensure mt_rand() is reseeded before calling it the first time.
          mt_srand();
        }
        
        do {
          $random_state = hash('sha256', microtime() . mt_rand() . $random_state);
          $bytes .= hash('sha256', mt_rand() . $random_state, TRUE);
        } while (strlen($bytes) < $count);
      }
      $output = substr($bytes, 0, $count);
      $bytes = substr($bytes, $count);
      return $output;
    }
  }
}