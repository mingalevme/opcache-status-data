<?php

declare(strict_types=1);

namespace Mingalevme\OpcacheStatusInfo\Fetcher;

use Mingalevme\OpcacheStatusInfo\Fetcher;
use Mingalevme\OpcacheStatusInfo\OpcacheStatusInfo;

class PuppetFetcher implements Fetcher
{
    protected array $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function fetch(): OpcacheStatusInfo
    {
        return new OpcacheStatusInfo($this->info);
    }
}
