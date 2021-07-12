<?php

declare(strict_types=1);

namespace Mingalevme\Tests\OpcacheStatusInfo;

use Mingalevme\OpcacheStatusInfo\OpcacheStatusInfo;
use Mingalevme\OpcacheStatusInfo\OpcacheStatusScriptInfo;

class OpcacheStatusInfoTest extends TestCase
{
    private const CURRENT_WASTED_PERCENTAGE = 0.021731853485107422;
    private const OPCACHE_HIT_RATE = 93.79733141002524;

    public function testParsing(): void
    {
        $opcacheStatusInfo = new OpcacheStatusInfo($this->getData());
        // general
        self::assertSame(true, $opcacheStatusInfo->isEnabled());
        self::assertSame(true, $opcacheStatusInfo->isFull());
        self::assertSame(true, $opcacheStatusInfo->isAwaitingRestart());
        self::assertSame(true, $opcacheStatusInfo->isRestartInProgress());
        // memory usage
        self::assertSame(16229216, $opcacheStatusInfo->getMemoryUsed());
        self::assertSame(117959344, $opcacheStatusInfo->getMemoryFree());
        self::assertSame(29168, $opcacheStatusInfo->getMemoryWasted());
        self::assertLessThan(1.0E-6, abs($opcacheStatusInfo->getMemoryCurrentWastedRatio() - self::CURRENT_WASTED_PERCENTAGE / 100.0));
        // interned strings
        self::assertSame(6291008, $opcacheStatusInfo->getInternedStringsBufferSize());
        self::assertSame(2126848, $opcacheStatusInfo->getInternedStringsMemoryUsed());
        self::assertSame(4164160, $opcacheStatusInfo->getInternedStringsMemoryFree());
        self::assertSame(29926, $opcacheStatusInfo->getInternedStringsCount());
        // opcache_statistics
        self::assertSame(333, $opcacheStatusInfo->getOpcacheNumCachedScripts());
        self::assertSame(633, $opcacheStatusInfo->getOpcacheNumCachedKeys());
        self::assertSame(16229, $opcacheStatusInfo->getOpcacheMaxCachedKeys());
        self::assertSame(5202, $opcacheStatusInfo->getOpcacheHits());
        self::assertSame(1626072691, $opcacheStatusInfo->getOpcacheStartTime());
        self::assertSame(0, $opcacheStatusInfo->getOpcacheLastRestartTime());
        self::assertSame(344, $opcacheStatusInfo->getOpcacheMisses());
        self::assertSame(1, $opcacheStatusInfo->getOpcacheOomRestarts());
        self::assertSame(1, $opcacheStatusInfo->getOpcacheHashRestarts());
        self::assertSame(1, $opcacheStatusInfo->getOpcacheManualRestarts());
        self::assertSame(1, $opcacheStatusInfo->getOpcacheBlacklistMisses());
        self::assertSame(0.01, $opcacheStatusInfo->getOpcacheBlacklistMissRatio());
        self::assertLessThan(1.0E-6, abs($opcacheStatusInfo->getOpcacheHitRate() - self::OPCACHE_HIT_RATE / 100.0));
        // jit
        self::assertSame(true, $opcacheStatusInfo->getJitIsEnabled());
        self::assertSame(true, $opcacheStatusInfo->getJitIsOn());
        self::assertSame(5, $opcacheStatusInfo->getJitKind());
        self::assertSame(4, $opcacheStatusInfo->getJitOptLevel());
        self::assertSame(6, $opcacheStatusInfo->getJitOptFlags());
        self::assertSame(100, $opcacheStatusInfo->getJitBufferSize());
        self::assertSame(10, $opcacheStatusInfo->getJitBufferFree());
        // scripts
        $scripts = iterator_to_array($opcacheStatusInfo->getScripts());
        self::assertCount(1, $scripts);
        /** @var OpcacheStatusScriptInfo $opcacheStatusScriptData */
        $opcacheStatusScriptData = $scripts[0];
        self::assertSame('/app/file.php', $opcacheStatusScriptData->getFullPath());
        self::assertSame(1, $opcacheStatusScriptData->getHits());
        self::assertSame(5608, $opcacheStatusScriptData->getMemoryConsumption());
        self::assertSame('Mon Jul 12 07:20:10 2021', $opcacheStatusScriptData->getLastUsed());
        self::assertSame(1626074410, $opcacheStatusScriptData->getLastUsedTimestamp());
        self::assertSame(1607951725, $opcacheStatusScriptData->getTimestamp());
    }

    public function testEmptyScriptsArrayTest(): void
    {
        $opcacheStatusData = new OpcacheStatusInfo($this->getEmptyScriptsArrayData());
        $count = 0;
        // Check iterating runtime errors
        foreach ($opcacheStatusData->getScripts() as $opcacheScriptInfo) {
            $count++;
        }
        self::assertSame(0, $count);
    }

    protected function getData(): array
    {
        return [
            'opcache_enabled' => true,
            'cache_full' => true,
            'restart_pending' => true,
            'restart_in_progress' => true,
            'memory_usage' => [
                'used_memory' => 16229216,
                'free_memory' => 117959344,
                'wasted_memory' => 29168,
                'current_wasted_percentage' => self::CURRENT_WASTED_PERCENTAGE,
            ],
            'interned_strings_usage' => [
                'buffer_size' => 6291008,
                'used_memory' => 2126848,
                'free_memory' => 4164160,
                'number_of_strings' => 29926,
            ],
            'opcache_statistics' => [
                'num_cached_scripts' => 333,
                'num_cached_keys' => 633,
                'max_cached_keys' => 16229,
                'hits' => 5202,
                'start_time' => 1626072691,
                'last_restart_time' => 0,
                'oom_restarts' => 1,
                'hash_restarts' => 1,
                'manual_restarts' => 1,
                'misses' => 344,
                'blacklist_misses' => 1,
                'blacklist_miss_ratio' => 1.0,
                'opcache_hit_rate' => self::OPCACHE_HIT_RATE,
            ],
            'jit' => [
                'enabled' => true,
                'on' => true,
                'kind' => 5,
                'opt_level' => 4,
                'opt_flags' => 6,
                'buffer_size' => 100,
                'buffer_free' => 10,
            ],
            'scripts' => [
                '/app/file.php' => [
                    'full_path' => '/app/file.php',
                    'hits' => 1,
                    'memory_consumption' => 5608,
                    'last_used' => 'Mon Jul 12 07:20:10 2021',
                    'last_used_timestamp' => 1626074410,
                    'timestamp' => 1607951725,
                ],
            ]
        ];
    }

    protected function getEmptyScriptsArrayData(): array
    {
        return [
            'scripts' => [],
        ] + $this->getData();
    }
}
