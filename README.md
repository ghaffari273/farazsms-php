# farazsms/sdk

**FarazSMS · IranPayamak** — فراز اس ام اس · ایران پیامک
🌐 [farazsms.com](https://farazsms.com) · [iranpayamak.com](https://iranpayamak.com)

Official PHP client for the **FarazSMS / IranPayamak** web services — send pattern/OTP, simple & bulk SMS, pull reports, manage your phonebook, and reach **all 63 endpoints** through a low-level `request()`.

PHP 7.4+ · PSR-4 · throws on API errors.

## Install

```bash
composer require farazsms/sdk
```

## Quick start

```php
<?php
require "vendor/autoload.php";

use FarazSMS\FarazSMS;
use FarazSMS\FarazException;

$sms = new FarazSMS("YOUR_API_KEY"); // key from the panel → Web Service / API Key

print_r($sms->balance());                                              // verify the key — free
$sms->sendPattern("SJ3FgPrE0C", "09120000000", ["code" => "1234"]);    // OTP (instant)
$sms->sendSimple("Hello!", ["09120000000", "09130000000"]);           // bulk
print_r($sms->inbox(1, 20));                                          // inbound replies

try {
    $sms->sendPattern("BAD", "09120000000", ["code" => "1"]);
} catch (FarazException $e) {
    echo $e->statusCode . " " . $e->getMessage();
}
```

> Recipients use the local format `09120000000` (no `+98`). Default sender line `90008361`.

## Bundled helpers

| Area | Methods |
|------|---------|
| Account | `balance()` · `profile()` · `lines()` |
| Send | `sendPattern()` · `sendSimple()` · `sendVariable()` |
| Patterns | `createPattern()` · `patterns()` |
| Reports | `inbox()` · `sendRequests()` · `sendRequestItems()` |
| Phonebook | `phonebooks()` · `addContact()` |
| Reference | `provinces()` · `numberBanks()` |

**Anything else** (tickets, orders, voice, LBS, …) via `request($method, $path, $body)`.

## License

MIT
