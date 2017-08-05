<?php

namespace Wapi\Protocol\Exception;

class AccessDenied extends WapiException  {
  const CODE = 4;
  const MESSAGE = 'Access denied.';
}