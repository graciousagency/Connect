# Robin/Connect

This package connects your SEOShop webshop to your ROBIN account. It leverages both of the API's to form an 
integration.

## Installation

### Laravel / Lumen
Simply do:
```BASH
$ composer require graciousstudios/connect
```

And register `RobinConnectSEOShopServiceProvider::class` as a service provider. For lumen, inside `bootstrap/app.php`
add `$app->register(RobinConnectSEOShopServiceProvider::class);`. For Laravel, add it to the providers array inside 
`config/app.php`
 
## Setup
To be able to use robin/connect, you need to add the following environment variables to your .env file:

```text
##SEOSHOP KEYS
SEOSHOP_API_KEY=
SEOSHOP_API_SECRET=
SEOSHOP_API_LANGUAGE=

##ROBIN KEYS
ROBIN_API_KEY=
ROBIN_API_SECRET=
ROBIN_API_URL=https://api.robinhq.com/

PAPERTRAIL_APP_NAME=
```

These keys make it possible to work with the different API's

## Usage
