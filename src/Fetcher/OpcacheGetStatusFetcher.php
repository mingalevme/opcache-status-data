<?php

declare(strict_types=1);

namespace Mingalevme\OpcacheStatusInfo\Fetcher;

use Mingalevme\OpcacheStatusInfo\Fetcher;
use Mingalevme\OpcacheStatusInfo\OpcacheStatusInfo;
use RuntimeException;

/**
 * Real fetcher, requires opcache extension
 */
class OpcacheGetStatusFetcher implements Fetcher
{
    public function fetch(): OpcacheStatusInfo
    {
        $info = opcache_get_status(false);
        if (false === $info) {
            throw new RuntimeException('Error while getting opcache status');
        }
        return new OpcacheStatusInfo($info);
    }
}
