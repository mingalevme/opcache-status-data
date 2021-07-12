# PHP OPcache status information object

The PHP `opcache_get_status`-function result wrapper.

## Installation
```shell
composer require mingalevme/opcache-status-info
````

## Usage

```php
# some bootstrap file

use Mingalevme\OpcacheStatusInfo\Fetcher as OpcacheStatusInfoFetcher;
use Mingalevme\OpcacheStatusInfo\Fetcher\OpcacheGetStatusFetcher;

$fetcher = new OpcacheGetStatusFetcher();

$someDIContainer->bind(OpcacheStatusInfoFetcher::class, function() use ($fetcher): OpcacheStatusInfoFetcher {
    return $fetcher;
});
````

```php
# some app file

use Mingalevme\OpcacheStatusInfo\Fetcher;
use Mingalevme\OpcacheStatusInfo\OpcacheStatusScriptInfo;

/** @var Fetcher $fetcher */
$fetcher = $someDIContainer->get(Fetcher::class);

$opcacheStatusInfo = $fetcher->fetch();

echo $opcacheStatusInfo->isEnabled();

/** @var OpcacheStatusScriptInfo $opcacheStatusScriptInfo */
foreach ($opcacheStatusInfo->getScripts() as $opcacheStatusScriptInfo) {
    echo $opcacheStatusScriptInfo->getFullPath();
}
```
