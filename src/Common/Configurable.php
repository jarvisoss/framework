<?php

namespace Jarvis\Framework\Common;

use Jarvis\Framework\Config\Config;

interface Configurable
{
    public function configure(Config $config);
}
