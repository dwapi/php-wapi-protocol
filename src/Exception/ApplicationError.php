<?php

namespace Wapi\Protocol\Exception;

class ApplicationError extends WapiProtocolException  {
  const CODE = 2;
  const MESSAGE = 'Application error.';
}