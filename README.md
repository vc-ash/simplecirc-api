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
>**Note**: Since this is still incomplete the composer package has yet to be published. So we pull in the repository instead of the package.
>It also currently depends on some helper functions that aren't published composer packages yet, so be we include that repository in the dependency as well.
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

```
composer update
```

# Usage


## Start with
>**Note**: Use the your credentials from SimpleCirc with the API.

```php
use SimpleCircApi\Api;
use SimpleCircApi\Subscriber;
use SimpleCircApi\Address;
use SimpleCircApi\NewSubscription;

//include the composer autoload file if it's not already included in your project.
require_once ('./vendor/autoload.php'); 

$api_user = 'your_user_api_username';
$api_key = 'your_user_api_key';

$simpleCircApi = new Api($api_user, $api_key);
```

Once you have that down, you are ready to use the API.

## getSubscribers()
### Getting a list of subscribers
>**Note**: There are 2 optional params you can pass to the getSubscribers function. 1) `limit` and 2) `email`. In the example below I've limited it to return a max of 3 subscribers.
```php
try{
    $subscribers = $simpleCircApi->getSubscribers(3);
    pre_print($subscribers);
}
catch(Exception $e){
    die($e->getMessage());
}
```

### Get Subscribers by Email address
>**Note**: There are optional params you can pass to the getSubscribers function. `limit` and `email` respectively. In the example below it's limited to return a max of 3 subscribers.
```php
try{
    $subscribers = $simpleCircApi->getSubscribers(5, 'orange.joe@example.com');
    pre_print($subscribers);
}
catch(Exception $e){
    die($e->getMessage());
}
```


## getSubscriber()
### Get a Subscriber by their account ID
>**Note**: Only 1 param: account_id.
```php
try{
    $subscriber = $simpleCircApi->getSubscriber(4742017184);
    pre_print($subscriber);
}
catch(Exception $e){
    die($e->getMessage());
}
```

## Subscriber (object)
Each `Subscriber` is an object, so you can access their properties by using camelCase get methods like so:
```php
echo 'Account ID: ' . $subscriber->getAccountId();
echo 'Name: ' . $subscriber->getName();
echo 'Email: ' . $subscriber->getEmail();
echo 'Company: ' . $subscriber->getCompany();
echo 'Renewal Link: ' . $subscriber->getRenewalLink();
```

## Address (object)
Each `Subscriber` has an `Address` object attached to them. You can access their `Address` properties like so:
```php
echo 'Address 1: ' . $subscriber->getAddress()->getAddress1();
echo 'Address 1: ' . $subscriber->getAddress()->getAddress2();
echo 'City: ' . $subscriber->getAddress()->getCity();
echo 'State: ' . $subscriber->getAddress()->getState();
echo 'Zip: ' . $subscriber->getAddress()->getZipcode();
echo 'Country: ' . $subscriber->getAddress()->getCountry();
```


## Subscription (object)
Each Subscriber has an array of `Subscription` objects attached to them if they have subscriptions. You can access their `Subscription` properties like so:
```php
echo 'Subscription ID: ' . $subscriber->getSubscriptions()[0]->getSubscriptionId();
echo 'Publication ID: ' . $subscriber->getSubscriptions()[0]->getPublicationId();
echo 'Publication Name: ' . $subscriber->getSubscriptions()[0]->getPublicationName();
echo 'Status: ' . $subscriber->getSubscriptions()[0]->getStatus();
echo 'Digital Status: ' . $subscriber->getSubscriptions()[0]->getDigitalStatus();
echo 'Expiration Date: ' . $subscriber->getSubscriptions()[0]->getExpirationDate();
echo 'Copies: ' . $subscriber->getSubscriptions()[0]->getCopies();
echo 'Issues Remaining: ' . $subscriber->getSubscriptions()[0]->getIssuesRemaining();
```

