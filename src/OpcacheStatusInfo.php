<?php

declare(strict_types=1);

namespace Mingalevme\OpcacheStatusInfo;

use Generator;

/**
 * https://stackoverflow.com/questions/23517542/what-does-zend-opcaches-num-cached-keys-statistic-mean
 * https://rtfm.co.ua/php-keshirovanie-php-skriptov-nastrojka-i-tyuning-opcache/
 * https://help.zend.com/zend/zend-server-8-ibmi/content/zendopcache_phpapi.htm
 *
 * @psalm-type OpcacheStatusScriptArray=array{
 *      full_path: string,
 *      hits: int,
 *      memory_consumption: int,
 *      last_used: string,
 *      last_used_timestamp: int,
 *      timestamp: int,
 *  }
 */
class OpcacheStatusInfo
{
    protected array $opcacheStatusInfo;

    public function __construct(array $opcacheStatusData)
    {
        $this->opcacheStatusInfo = $opcacheStatusData;
        $this->opcacheStatusInfo['scripts'] = (array)($this->opcacheStatusInfo['scripts'] ?? []);
    }

    public function isEnabled(): bool
    {
        return !empty($this->opcacheStatusInfo['opcache_enabled']);
    }

    public function isFull(): bool
    {
        return !empty($this->opcacheStatusInfo['cache_full']);
    }

    public function isAwaitingRestart(): bool
    {
        return !empty($this->opcacheStatusInfo['restart_pending']);
    }

    public function isRestartInProgress(): bool
    {
        return !empty($this->opcacheStatusInfo['restart_in_progress']);
    }

    // memory_usage

    public function getMemoryUsed(): int
    {
        return intval($this->opcacheStatusInfo['memory_usage']['used_memory'] ?? 0);
    }

    public function getMemoryFree(): int
    {
        return intval($this->opcacheStatusInfo['memory_usage']['free_memory'] ?? 0);
    }

    public function getMemoryWasted(): int
    {
        return intval($this->opcacheStatusInfo['memory_usage']['wasted_memory'] ?? 0);
    }

    public function getMemoryCurrentWastedRatio(): float
    {
        return floatval($this->opcacheStatusInfo['memory_usage']['current_wasted_percentage'] ?? 0.0) / 100.0;
    }

    // interned_strings_usage

    public function getInternedStringsBufferSize(): int
    {
        return intval($this->opcacheStatusInfo['interned_strings_usage']['buffer_size'] ?? 0);
    }

    public function getInternedStringsMemoryUsed(): int
    {
        return intval($this->opcacheStatusInfo['interned_strings_usage']['used_memory'] ?? 0);
    }

    public function getInternedStringsMemoryFree(): int
    {
        return intval($this->opcacheStatusInfo['interned_strings_usage']['free_memory'] ?? 0);
    }

    public function getInternedStringsCount(): int
    {
        return intval($this->opcacheStatusInfo['interned_strings_usage']['number_of_strings'] ?? 0);
    }

    // opcache_statistics

    public function getOpcacheNumCachedScripts(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['num_cached_scripts'] ?? 0);
    }

    public function getOpcacheNumCachedKeys(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['num_cached_keys'] ?? 0);
    }

    public function getOpcacheMaxCachedKeys(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['max_cached_keys'] ?? 0);
    }

    public function getOpcacheHits(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['hits'] ?? 0);
    }

    public function getOpcacheStartTime(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['start_time'] ?? 0);
    }

    public function getOpcacheLastRestartTime(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['last_restart_time'] ?? 0);
    }

    public function getOpcacheMisses(): int
    {
        return intval($this->opcacheStatusInfo['opcache_statistics']['misses'] ?? 0);
    }

    public function getOpcacheHitRate(): float
    {
        return floatval($this->opcacheStatusInfo['opcache_statistics']['opcache_hit_rate'] ?? 0) / 100.0;
    }

    // jit

    public function getJitIsEnabled(): bool
    {
        return !empty($this->opcacheStatusInfo['jit']['enabled']);
    }

    public function getJitIsOn(): bool
    {
        return !empty($this->opcacheStatusInfo['jit']['on']);
    }

    public function getJitKind(): int
    {
        return intval($this->opcacheStatusInfo['jit']['kind'] ?? 0);
    }

    public function getJitOptLevel(): int
    {
        return intval($this->opcacheStatusInfo['jit']['opt_level'] ?? 0);
    }

    public function getJitOptFlags(): int
    {
        return intval($this->opcacheStatusInfo['jit']['opt_flags'] ?? 0);
    }

    public function getJitBufferSize(): int
    {
        return intval($this->opcacheStatusInfo['jit']['buffer_size'] ?? 0);
    }

    public function getJitBufferFree(): int
    {
        return intval($this->opcacheStatusInfo['jit']['buffer_free'] ?? 0);
    }

    /**
     * @return Generator<OpcacheStatusScriptInfo>
     */
    public function getScripts(): Generator
    {
        if (empty($this->opcacheStatusInfo['scripts'])) {
            return;
            // yield from [];
        }

        /**
         * @var array $datum
         * @psalm-var OpcacheStatusScriptArray $datum
         */
        foreach ($this->opcacheStatusInfo['scripts'] as $datum) {
            yield new OpcacheStatusScriptInfo($datum);
        }
    }
}
