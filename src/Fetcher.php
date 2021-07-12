<?php

declare(strict_types=1);

namespace Mingalevme\OpcacheStatusInfo;

interface Fetcher
{
    public function fetch(): OpcacheStatusInfo;
}
