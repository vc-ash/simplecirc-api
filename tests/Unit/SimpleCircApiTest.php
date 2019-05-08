<?php
namespace Tests\Unit;

use VcAsh\SimpleCircApi;
use VcAsh\Exception\ApiError;
use VcAsh\Subscriber;
use VcAsh\Address;
use VcAsh\Subscription;
use VcAsh\NewSubscription;
use PHPUnit_Framework_TestCase;

class SimpleCircApiTest extends PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        $this->simpleCircApi = new SimpleCircApi('Bearer', 'Gq0LcmyRFh2zMWByimoNJzluVd7fbX8PJMQh27vYnAOYCrNNJrMkvV9kT1Zs');
    }
    
    public function subscriberDataProvider()
    {
        return $subscriber_info = [
            0 => [[
                'name' => 'Orange Joe',
                'first_name' => 'Orange',
                'last_name' => 'Joe',
                'email' => 'orange.joe@example.com',
                'company' => 'Orange Joe Co.',
                'address' => [
                    'address_1' => '4242 Test Drive',
                    'address_2' => 'Apt 47',
                    'city' => 'New New York',
                    'state' => 'NY',
                    'zipcode' => '01247',
                    'country' => 'United States'
                ],
                'subscriptions' => [
                    [
                        'publication_id' => 205,
                        'issues_purchased' => 3,
                        'copies' => 1,
                        'postage_id' => 13810,
                        'promo_code' => 'ORANGEJOEPROMO',
                        'giftgiver_account_id' => null,
                        'never_expires' => 0,
                        'amount_paid' => 7.47,
                        'amount_due' => 4.74,
                        'tax_amount' => 0.00,
                        'currency' => 'USD'
                    ]
                ]
            ]]
        ];
    }
    
    /**
     * @expectedException VcAsh\Exception\ApiError
     * @expectedExceptionCode 0
     * @expectedExceptionCode Api Error: Unauthenticated
     */
    public function test_aApiCallWithInvalidPassword_expectExceptionApiError()
    {
        $this->simpleCircApi->setApiKey('invalid_key');
        $subscribers = $this->simpleCircApi->getSubscribers(1);
        
        throw new ApiError('Api Error: Unauthenticated', 0);
    }
    
    public function test_getSubscribers_withLimit10_expectArrayOfObjectResults()
    {
        $subscribers = $this->simpleCircApi->getSubscribers(10);
        
        $this->assertNotEmpty($subscribers);
        $this->assertTrue(is_array($subscribers));
        $this->assertContainsOnlyInstancesOf(Subscriber::class, $subscribers);
    }
    
    public function test_getSubscribers_withLimit3AndEmail_expectArrayOfObjectResults()
    {
        $subscribers = $this->simpleCircApi->getSubscribers(3, 'ron.jones@example.com');
        
        $this->assertTrue(!empty($subscribers));
        $this->assertTrue(is_array($subscribers));
        $this->assertContainsOnlyInstancesOf(Subscriber::class, $subscribers);
    }
    
    /**
     * @dataProvider subscriberDataProvider
     */
    public function test_saveSubscriberIsNewByHydrationWithAddressAndNewSubscription_expectObjectOfResults($subscriber_info)
    {
       
        $subscriber = new Subscriber([
            'name' => $subscriber_info['name'],
            'email' => $subscriber_info['email'],
            'company' => $subscriber_info['company']
        ]);
        
        $subscriber->setAddress([
            'address_1' => $subscriber_info['address']['address_1'],
            'address_2' => $subscriber_info['address']['address_2'],
            'city' => $subscriber_info['address']['city'],
            'state' => $subscriber_info['address']['state'],
            'zipcode' => $subscriber_info['address']['zipcode'],
            'country' => $subscriber_info['address']['country']
        ]);
        
        $subscriber->addNewSubscriptions([[
            'publication_id' => $subscriber_info['subscriptions'][0]['publication_id'],
            'issues_purchased' => $subscriber_info['subscriptions'][0]['issues_purchased'],
            'copies' => $subscriber_info['subscriptions'][0]['copies'],
            'postage_id' => $subscriber_info['subscriptions'][0]['postage_id'],
            'promo_code' => $subscriber_info['subscriptions'][0]['promo_code'],
            'giftgiver_account_id' => $subscriber_info['subscriptions'][0]['giftgiver_account_id'],
            'never_expires' => $subscriber_info['subscriptions'][0]['never_expires'],
            'amount_paid' => $subscriber_info['subscriptions'][0]['amount_paid'],
            'amount_due' => $subscriber_info['subscriptions'][0]['amount_due'],
            'tax_amount' => $subscriber_info['subscriptions'][0]['tax_amount'],
            'currency' => $subscriber_info['subscriptions'][0]['currency']
        ]]);
        
        $result = $this->simpleCircApi->saveSubscriber($subscriber);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Subscriber::class, $result);
        $this->assertEquals(strtoupper($subscriber_info['first_name']), $result->getFirstName());
        $this->assertEquals(strtoupper($subscriber_info['last_name']), $result->getLastName());
        $this->assertStringStartsWith('http', $result->getRenewalLink());
        $this->assertInstanceOf(Address::class, $result->getAddress());
        
        $this->assertNotEmpty($result->getSubscriptions());
        $this->assertTrue(is_array($result->getSubscriptions()));
        $this->assertContainsOnlyInstancesOf(Subscription::class, $result->getSubscriptions());
        
        $this->assertNotEmpty($result->getSubscriptions()[0]->getSubscriptionId());
        $this->assertEquals($subscriber_info['subscriptions'][0]['publication_id'], $result->getSubscriptions()[0]->getPublicationId());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getPublicationName());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getStatus());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getDigitalStatus());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getExpirationDate());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getIssuesRemaining());
        $this->assertEmpty($result->getSubscriptions()[0]->getGiftgiver());
    }
    
    /**
     * @dataProvider subscriberDataProvider
     */
    public function test_saveSubscriberIsNewByHydrationWithNewAddressAndNewSubscription_expectObjectOfResults($subscriber_info)
    {
       
        $subscriber = new Subscriber([
            'name' => $subscriber_info['name'],
            'email' => $subscriber_info['email'],
            'company' => $subscriber_info['company']
        ]);
        
        $subscriber->setNewAddress([
            'address_1' => $subscriber_info['address']['address_1'],
            'address_2' => $subscriber_info['address']['address_2'],
            'city' => $subscriber_info['address']['city'],
            'state' => $subscriber_info['address']['state'],
            'zipcode' => $subscriber_info['address']['zipcode'],
            'country' => $subscriber_info['address']['country']
        ]);
        
        $subscriber->addNewSubscription([
            'publication_id' => $subscriber_info['subscriptions'][0]['publication_id'],
            'issues_purchased' => $subscriber_info['subscriptions'][0]['issues_purchased'],
            'copies' => $subscriber_info['subscriptions'][0]['copies'],
            'postage_id' => $subscriber_info['subscriptions'][0]['postage_id'],
            'promo_code' => $subscriber_info['subscriptions'][0]['promo_code'],
            'giftgiver_account_id' => $subscriber_info['subscriptions'][0]['giftgiver_account_id'],
            'never_expires' => $subscriber_info['subscriptions'][0]['never_expires'],
            'amount_paid' => $subscriber_info['subscriptions'][0]['amount_paid'],
            'amount_due' => $subscriber_info['subscriptions'][0]['amount_due'],
            'tax_amount' => $subscriber_info['subscriptions'][0]['tax_amount'],
            'currency' => $subscriber_info['subscriptions'][0]['currency']
        ]);
        
        $result = $this->simpleCircApi->saveSubscriber($subscriber);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Subscriber::class, $result);
        $this->assertEquals(strtoupper($subscriber_info['first_name']), $result->getFirstName());
        $this->assertEquals(strtoupper($subscriber_info['last_name']), $result->getLastName());
        $this->assertStringStartsWith('http', $result->getRenewalLink());
        $this->assertInstanceOf(Address::class, $result->getAddress());
        
        $this->assertNotEmpty($result->getSubscriptions());
        $this->assertTrue(is_array($result->getSubscriptions()));
        $this->assertContainsOnlyInstancesOf(Subscription::class, $result->getSubscriptions());
        
        $this->assertNotEmpty($result->getSubscriptions()[0]->getSubscriptionId());
        $this->assertEquals($subscriber_info['subscriptions'][0]['publication_id'], $result->getSubscriptions()[0]->getPublicationId());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getPublicationName());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getStatus());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getDigitalStatus());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getExpirationDate());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getIssuesRemaining());
        $this->assertEmpty($result->getSubscriptions()[0]->getGiftgiver());
    }
    
    /**
     * @dataProvider subscriberDataProvider
     */
    public function test_saveSubscriberIsNewByInstantiationWithNewAddressObjectAndNewSubscriptionObject_expectObjectOfResults($subscriber_info)
    {
        // Create the Subscriber object and set the baseic info
        $subscriber = new Subscriber();
        $subscriber->setName($subscriber_info['name'])
            ->setEmail($subscriber_info['email'])
            ->setCompany($subscriber_info['company']);
        
        // Create the Address object and set the address info
        $address = new Address();
        $address->setAddress1($subscriber_info['address']['address_1'])
            ->setAddress2($subscriber_info['address']['address_2'])
            ->setCity($subscriber_info['address']['city'])
            ->setState($subscriber_info['address']['state'])
            ->setZipcode($subscriber_info['address']['zipcode'])
            ->setCountry($subscriber_info['address']['country']);
        
        // Add the address to the subscriber
        $subscriber->setNewAddress($address);
        
        // Create the NewSubscription object and set the subscription info
        $subscription = new NewSubscription();
        $subscription->setPublicationId($subscriber_info['subscriptions'][0]['publication_id'])
            ->setIssuesPurchased($subscriber_info['subscriptions'][0]['issues_purchased'])
            ->setCopies($subscriber_info['subscriptions'][0]['copies'])
            ->setPostageId($subscriber_info['subscriptions'][0]['postage_id'])
            ->setPromoCode($subscriber_info['subscriptions'][0]['promo_code'])
            ->setGiftgiverAccountId($subscriber_info['subscriptions'][0]['giftgiver_account_id'])
            ->setNeverExpires($subscriber_info['subscriptions'][0]['never_expires'])
            ->setAmountPaid($subscriber_info['subscriptions'][0]['amount_paid'])
            ->setAmountDue($subscriber_info['subscriptions'][0]['amount_due'])
            ->setTaxAmount($subscriber_info['subscriptions'][0]['tax_amount'])
            ->setCurrency($subscriber_info['subscriptions'][0]['currency']);
        
        // Add the new subscription to the subscriber
        $subscriber->addNewSubscription($subscription);
        
        // Save the new subscriber to SimpleCirc
        $result = $this->simpleCircApi->saveSubscriber($subscriber);
        
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Subscriber::class, $result);
        $this->assertEquals(strtoupper($subscriber_info['first_name']), $result->getFirstName());
        $this->assertEquals(strtoupper($subscriber_info['last_name']), $result->getLastName());
        $this->assertStringStartsWith('http', $result->getRenewalLink());
        $this->assertInstanceOf(Address::class, $result->getAddress());
        
        $this->assertNotEmpty($result->getSubscriptions());
        $this->assertTrue(is_array($result->getSubscriptions()));
        $this->assertContainsOnlyInstancesOf(Subscription::class, $result->getSubscriptions());
        
        $this->assertNotEmpty($result->getSubscriptions()[0]->getSubscriptionId());
        $this->assertEquals($subscriber_info['subscriptions'][0]['publication_id'], $result->getSubscriptions()[0]->getPublicationId());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getPublicationName());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getStatus());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getDigitalStatus());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getExpirationDate());
        $this->assertNotEmpty($result->getSubscriptions()[0]->getIssuesRemaining());
        $this->assertEmpty($result->getSubscriptions()[0]->getGiftgiver());
    }
    
    public function test_getSubscriber_expectObjectOfResults()
    {
        $subscriber = $this->simpleCircApi->getSubscriber('12375794');
        
        $this->assertNotEmpty($subscriber);
        $this->assertContainsOnlyInstancesOf(Subscriber::class, [$subscriber]);
    }
    
    /**
     * @dataProvider subscriberDataProvider
     */
    public function test_saveSubscriberWithNewBaseInfo_expectObjectOfResults($subscriber_info)
    {
        $modifier = '2updated_';
        $subscriber = $this->simpleCircApi->getSubscriber('12375794');
        $subscriber->setNewName($modifier.$subscriber_info['name'])
            ->setNewEmail($modifier.$subscriber_info['email'])
            ->setNewCompany($modifier.$subscriber_info['company']);
        
        $updatedSubscriber = $this->simpleCircApi->saveSubscriber($subscriber);
        
        $this->assertNotEmpty($updatedSubscriber);
        $this->assertInstanceOf(Subscriber::class, $updatedSubscriber);
        $this->assertEquals(strtoupper($modifier.$subscriber_info['name']), $updatedSubscriber->getName());
        $this->assertEquals($modifier.$subscriber_info['email'], $updatedSubscriber->getEmail());
        $this->assertEquals(strtoupper($modifier.$subscriber_info['company']), $updatedSubscriber->getCompany());
        
        // Change the data back
        $updatedSubscriber->setNewName($subscriber_info['name'])
            ->setNewEmail($subscriber_info['email'])
            ->setNewCompany($subscriber_info['company']);
        
        $revertedSubscriber = $this->simpleCircApi->saveSubscriber($updatedSubscriber);
        
        $this->assertEquals(strtoupper($subscriber_info['name']), $revertedSubscriber->getName());
        $this->assertEquals($subscriber_info['email'], $revertedSubscriber->getEmail());
        $this->assertEquals(strtoupper($subscriber_info['company']), $revertedSubscriber->getCompany());
        
    }
    
    /**
     * @dataProvider subscriberDataProvider
     */
    public function test_saveSubscriberWithNewAddress_expectObjectOfResults($subscriber_info)
    {
        $modifier = '2updated_';
        $subscriber = $this->simpleCircApi->getSubscriber('12375794');
        
        $subscriber->setNewAddress([
            'address_1' => $modifier.$subscriber_info['address']['address_1'],
            'address_2' => $modifier.$subscriber_info['address']['address_2'],
            'city' => $modifier.$subscriber_info['address']['city'],
            'state' => $modifier.$subscriber_info['address']['state'],
            'zipcode' => $modifier.$subscriber_info['address']['zipcode'],
            'country' => $modifier.$subscriber_info['address']['country']
        ]);
        
        $updatedSubscriber = $this->simpleCircApi->saveSubscriber($subscriber);
        
        $this->assertNotEmpty($updatedSubscriber);
        $this->assertInstanceOf(Subscriber::class, $updatedSubscriber);
        $this->assertEquals(strtoupper($modifier.$subscriber_info['address']['address_1']), $updatedSubscriber->getAddress()->getAddress1());
        $this->assertEquals(strtoupper($modifier.$subscriber_info['address']['address_2']), $updatedSubscriber->getAddress()->getAddress2());
        $this->assertEquals(strtoupper($modifier.$subscriber_info['address']['city']), $updatedSubscriber->getAddress()->getCity());
        $this->assertEquals(strtoupper($modifier.$subscriber_info['address']['state']), $updatedSubscriber->getAddress()->getState());
        $this->assertEquals(strtoupper($modifier.$subscriber_info['address']['zipcode']), $updatedSubscriber->getAddress()->getZipcode());
        $this->assertEquals(strtoupper($modifier.$subscriber_info['address']['country']), $updatedSubscriber->getAddress()->getCountry());
        
        
        // Change the data back
        $updatedSubscriber->setNewAddress([
            'address_1' => $subscriber_info['address']['address_1'],
            'address_2' => $subscriber_info['address']['address_2'],
            'city' => $subscriber_info['address']['city'],
            'state' => $subscriber_info['address']['state'],
            'zipcode' => $subscriber_info['address']['zipcode'],
            'country' => $subscriber_info['address']['country']
        ]);
        
        $revertedSubscriber = $this->simpleCircApi->saveSubscriber($updatedSubscriber);
        
        $this->assertEquals(strtoupper($subscriber_info['address']['address_1']), $updatedSubscriber->getAddress()->getAddress1());
        $this->assertEquals(strtoupper($subscriber_info['address']['address_2']), $updatedSubscriber->getAddress()->getAddress2());
        $this->assertEquals(strtoupper($subscriber_info['address']['city']), $updatedSubscriber->getAddress()->getCity());
        $this->assertEquals(strtoupper($subscriber_info['address']['state']), $updatedSubscriber->getAddress()->getState());
        $this->assertEquals(strtoupper($subscriber_info['address']['zipcode']), $updatedSubscriber->getAddress()->getZipcode());
        $this->assertEquals(strtoupper($subscriber_info['address']['country']), $updatedSubscriber->getAddress()->getCountry());
        
    }
    
    
    /**
     * @dataProvider subscriberDataProvider
     */
    public function test_saveSubscriberWithNewSubscription_expectObjectOfResults($subscriber_info)
    {
        $subscriber = $this->simpleCircApi->getSubscriber('12375794');
        $initial_issues_remaining = $subscriber->getSubscriptions()[0]->getIssuesRemaining();
        
        $subscriber->addNewSubscription([
            'publication_id' => $subscriber_info['subscriptions'][0]['publication_id'],
            'issues_purchased' => $subscriber_info['subscriptions'][0]['issues_purchased'],
            'copies' => $subscriber_info['subscriptions'][0]['copies'],
            'postage_id' => $subscriber_info['subscriptions'][0]['postage_id'],
            'promo_code' => $subscriber_info['subscriptions'][0]['promo_code'],
            'giftgiver_account_id' => $subscriber_info['subscriptions'][0]['giftgiver_account_id'],
            'never_expires' => $subscriber_info['subscriptions'][0]['never_expires'],
            'amount_paid' => $subscriber_info['subscriptions'][0]['amount_paid'],
            'amount_due' => $subscriber_info['subscriptions'][0]['amount_due'],
            'tax_amount' => $subscriber_info['subscriptions'][0]['tax_amount'],
            'currency' => $subscriber_info['subscriptions'][0]['currency']
        ]);
        
        $updatedSubscriber = $this->simpleCircApi->saveSubscriber($subscriber);
        
        $this->assertNotEmpty($updatedSubscriber);
        $this->assertInstanceOf(Subscriber::class, $updatedSubscriber);
        $this->assertGreaterThan($initial_issues_remaining, $updatedSubscriber->getSubscriptions()[0]->getIssuesRemaining());
        $this->assertEquals($subscriber_info['subscriptions'][0]['issues_purchased'] + $initial_issues_remaining, $updatedSubscriber->getSubscriptions()[0]->getIssuesRemaining());
        
       
    }
    
   
}