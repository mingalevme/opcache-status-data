<?php

declare(strict_types=1);

namespace Mingalevme\Tests\OpcacheStatusInfo;

use Mingalevme\OpcacheStatusInfo\Fetcher\NullFetcher;
use Mingalevme\OpcacheStatusInfo\Fetcher\OpcacheGetStatusFetcher;
use Mingalevme\OpcacheStatusInfo\Fetcher\PuppetFetcher;

class FetcherTest extends TestCase
{
    public function testNullFetcher(): void
    {
        $info = (new NullFetcher())->fetch();
        self::assertSame(false, $info->isEnabled());
    }

    public function testPuppetFetcher(): void
    {
        $info = (new PuppetFetcher([
            'opcache_enabled' => true,
            'cache_full' => true,
        ]))->fetch();
        self::assertSame(true, $info->isEnabled());
        self::assertSame(true, $info->isFull());
    }

    public function testOpcacheGetInfoFetcher(): void
    {
        // Preventing IDEs from duplicating opcache_get_status's definition
        $functionName = 'opcache_' . 'get_' . 'status';
        if (!function_exists($functionName)) {
            eval("function $functionName(): array { return []; }");
        }
        $info = (new OpcacheGetStatusFetcher())->fetch();
        self::assertIsBool($info->isEnabled());
    }
}
