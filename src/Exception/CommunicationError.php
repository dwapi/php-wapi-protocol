<?php

namespace Wapi\Protocol\Exception;

class CommunicationError extends WapiException  {
  const CODE = 5;
  const MESSAGE = 'Communication error.';
}