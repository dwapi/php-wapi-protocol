<?php

namespace Wapi\Protocol\Exception;

class IncompleteConfiguration extends WapiException  {
  const CODE = 3;
  const MESSAGE = 'Incomplete configuration.';
}