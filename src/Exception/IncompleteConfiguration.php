<?php

namespace Wapi\Protocol\Exception;

class IncompleteConfiguration extends WapiProtocolException  {
  const CODE = 3;
  const MESSAGE = 'Incomplete configuration.';
}