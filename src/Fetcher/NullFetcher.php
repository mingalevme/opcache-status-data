<?php

declare(strict_types=1);

namespace Mingalevme\OpcacheStatusInfo\Fetcher;

class NullFetcher extends PuppetFetcher
{
    public function __construct()
    {
        parent::__construct([]);
    }
}
