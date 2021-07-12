<?php

declare(strict_types=1);

namespace Mingalevme\OpcacheStatusInfo;

/**
 * @psalm-type OpcacheStatusScriptArray=array{
 *      full_path: string,
 *      hits: int,
 *      memory_consumption: int,
 *      last_used: string,
 *      last_used_timestamp: int,
 *      timestamp: int,
 *  }
 */
class OpcacheStatusScriptInfo
{
    /**
     * @var array
     * @psalm-var OpcacheStatusScriptArray
     */
    private array $opcacheStatusScriptInfo;

    /**
     * @param array $opcacheStatusScriptInfo
     * @psalm-param OpcacheStatusScriptArray $opcacheStatusScriptInfo
     */
    public function __construct(array $opcacheStatusScriptInfo)
    {
        $this->opcacheStatusScriptInfo = $opcacheStatusScriptInfo;
    }

    public function getFullPath(): string
    {
        return $this->opcacheStatusScriptInfo['full_path'] ;
    }

    public function getHits(): int
    {
        return $this->opcacheStatusScriptInfo['hits'] ;
    }

    public function getMemoryConsumption(): int
    {
        return $this->opcacheStatusScriptInfo['memory_consumption'] ;
    }

    public function getLastUsed(): string
    {
        return $this->opcacheStatusScriptInfo['last_used'] ;
    }

    public function getLastUsedTimestamp(): int
    {
        return $this->opcacheStatusScriptInfo['last_used_timestamp'] ;
    }

    public function getTimestamp(): int
    {
        return $this->opcacheStatusScriptInfo['timestamp'] ;
    }
}
