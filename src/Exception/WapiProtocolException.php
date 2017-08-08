<?php

namespace Wapi\Protocol\Exception;

class WapiProtocolException extends \Exception {
  const CODE = 1;
  const MESSAGE = 'Error';
  
  public function __construct($message = NULL, $code = NULL) {
    $message = $message ?: static::MESSAGE;
    $code = $code ?: static::CODE;
    parent::__construct($message, $code);
  }
}
