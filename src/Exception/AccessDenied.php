<?php

namespace Wapi\Protocol\Exception;

class AccessDenied extends WapiProtocolException  {
  const CODE = 4;
  const MESSAGE = 'Access denied.';
}