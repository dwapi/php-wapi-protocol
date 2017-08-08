<?php

namespace Wapi\Protocol\Exception;

class CommunicationError extends WapiProtocolException  {
  const CODE = 5;
  const MESSAGE = 'Communication error.';
}