<?php

namespace Wapi\Protocol\Exception;

class ApplicationError extends WapiException  {
  const CODE = 2;
  const MESSAGE = 'Application error.';
}