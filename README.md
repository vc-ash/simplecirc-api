# SimpleCirc API interface for PHP
**This is under development...**

`SimpleCirc` is a magazine & newspaper subscription management SaaS designed for small publishers.
I'm building this client library for a friend, to work with their API. The goal is to make working with the API as easy as possible.
Here is a link to their [API documentation](https://simplecirc.com/docs/api)

**NOTES:**
This is not complete. Use with caution.


# Setup

## Composer

Pull this package in through Composer (development/latest version `dev-master`)

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/vc-ash/simplecirc-api"
        },
        {
            "type": "vcs",
            "url": "https://github.com/vc-ash/helper-functions"
        }
    ],
    "require": {
        "vc-ash/simplecirc-api": "dev-master",
        "vc-ash/helper-functions": "dev-master"
    }
}
```

    composer update


# Usage


## Start with
>**Note**: Use the yoru credentials from SimpleCirc with the API.

```php
use SimpleCircApi\Api;
use SimpleCircApi\Subscriber;
use SimpleCircApi\Address;
use SimpleCircApi\NewSubscription;

require_once ('./vendor/autoload.php');

$api_user = 'your_user_api_username';
$api_key = 'your_user_api_key';

$simpleCircApi = new Api($api_user, $api_key);
```

Once you have that down you can get a list of subscribers
## Get Subscribers
>**Note**: There are optional params you can pass to the getSubscribers function. `limit` and `email` respectively. In the example below it's limited to return a max of 3 subscribers.
```
try{
    $subscribers = $simpleCircApi->getSubscribers(3);
    pre_print($subscribers);
}
catch(Exception $e){
    die($e->getMessage());
}
```

