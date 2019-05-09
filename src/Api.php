<?php
namespace SimpleCircApi;

use SimpleCircApi\Exception\ApiError;

/**
 *  #Create a subscriber -> POST api/v1.2/subscribers
 *  #Retrieve a subscriber -> GET api/v1.2/subscribers/:account_id
 *  #Update a subscriber -> POST api/v1.2/subscribers/:account_id
 *  #List all subscribers -> GET api/v1.2/subscribers?limit=3
 *  #Create a subscrption -> POST api/v1.2/subscribers/:account_id/subscriptions
 *  #Renew a subscription -> POST api/v1.2/subscribers/:account_id/subscriptions
 *  #Create/update an address -> POST api/v1.2/subscribers/:account_id/addresses
 *  
 */
class Api {
    protected $api_url;
    protected $api_user;
    protected $api_key;
    protected $api_endpoint;
    
    public function __construct($api_user, $api_key)
    {
        $this->setApiUrl('https://simplecirc.com');
        $this->setApiUser($api_user);
        $this->setApiKey($api_key);
    }
    
    public function setApiUrl($value){
        $this->api_url = $value;
        return $this;
    }
    
    public function setApiUser($value){
        $this->api_user = $value;
        return $this;
    }
    
    public function setApiKey($value){
        $this->api_key = $value;
        return $this;
    }
    
    public function setApiEndpoint($value){
        $this->api_endpoint = $value;
        return $this;
    }
    
    public function getApiEndpoint(){
        return $this->api_endpoint;
    }
    
    public function getSubscribers($limit=3, $email='') {
        $parts['path'] = 'api/v1.2/subscribers';
        $parts['query'] = ['limit' => $limit];
        
        if(!empty($email)){
            $parts['query']['email'] = $email;
        }
        
        $response = $this->setApiEndpoint(build_url($this->api_url, $parts))
            ->sendRequest($this->getApiEndpoint());
        
        foreach($response->subscribers as &$subscriber){
            $subscriber = new Subscriber($subscriber); // Hydrate subscriber
        }
        
        return $response->subscribers;
    }
    
    public function getSubscriber($account_id) {
        $parts['path'] = 'api/v1.2/subscribers/'.$account_id;
        
        $response = $this->setApiEndpoint(build_url($this->api_url, $parts))
            ->sendRequest($this->getApiEndpoint());
        
        $subscriber = new Subscriber($response->subscriber); // Hydrate subscriber
        
        return $subscriber;
    }
    
    public function saveSubscriber(Subscriber $subscriber){
        // If new create subscriber
        if($subscriber->getIsNew() === true){
            return $this->createSubscriber($subscriber);
        }
        // Else update subscriber
        else{
            return $this->updateSubscriber($subscriber);
        }
    }
    
    public function createSubscriber(Subscriber $subscriber){
        $parts['path'] = 'api/v1.2/subscribers/';
        $post['name'] = (!empty($subscriber->getNewName()) ? $subscriber->getNewName() : $subscriber->getName());
        $post['email'] = (!empty($subscriber->getNewEmail()) ? $subscriber->getNewEmail() : $subscriber->getEmail());
        $post['company'] = (!empty($subscriber->getNewCompany()) ? $subscriber->getNewCompany() : $subscriber->getCompany());
        
        if(!empty($subscriber->getNewaddress())){
            
            $post['address_1'] = $subscriber->getNewaddress()->getAddress1();
            $post['address_2'] = $subscriber->getNewaddress()->getAddress2();
            $post['city'] = $subscriber->getNewaddress()->getCity();
            $post['state'] = $subscriber->getNewaddress()->getState();
            $post['zipcode'] = $subscriber->getNewaddress()->getZipcode();
            $post['country'] = $subscriber->getNewaddress()->getCountry();
            
        }
        else{
            $post['address_1'] = $subscriber->getAddress()->getAddress1();
            $post['address_2'] = $subscriber->getAddress()->getAddress2();
            $post['city'] = $subscriber->getAddress()->getCity();
            $post['state'] = $subscriber->getAddress()->getState();
            $post['zipcode'] = $subscriber->getAddress()->getZipcode();
            $post['country'] = $subscriber->getAddress()->getCountry();
        }
        
        $response = $this->setApiEndpoint(build_url($this->api_url, $parts))
            ->sendRequest($this->getApiEndpoint(), $post);
        
        $subscriber->hydrate($response->subscriber);
        $subscriber->unsetNewName()
            ->unsetNewEmail()
            ->unsetNewCompany();
        
        if(!empty($subscriber->getNewaddress())){
            $subscriber->unsetNewAddress();
        }
        
        
        if(!empty($subscriber->getNewSubscriptions())){
            
            foreach($subscriber->getNewSubscriptions() as $newSubscription){
                $result = $this->updateSubscriberSubscription($subscriber->getAccountId(), $newSubscription);
            }
            
            //TODO fix dependency on last interation returning valid results.
            $subscriber->hydrate($result);
            $subscriber->unsetNewSubscriptions();
        }
        
        return $subscriber;
    }
    
    public function updateSubscriber(Subscriber $subscriber){
        $parts['path'] = 'api/v1.2/subscribers/'.$subscriber->getAccountId();
        $post = [];
        
        if(!empty($subscriber->getNewName())){
            $post['name'] = $subscriber->getNewName();
        }
        if(!empty($subscriber->getNewEmail())){
            $post['email'] = $subscriber->getNewEmail();
        }
        if(!empty($subscriber->getNewCompany())){
            $post['company'] = $subscriber->getNewCompany();
        }
        
        if(!empty($post)){
            $response = $this->setApiEndpoint(build_url($this->api_url, $parts))
                ->sendRequest($this->getApiEndpoint(), $post);
            
            $subscriber->hydrate($response->subscriber);
            $subscriber->unsetNewName()
                ->unsetNewEmail()
                ->unsetNewCompany();
        }
        
        if(!empty($subscriber->getNewaddress())){
            $result = $this->updateSubscriberAddress($subscriber->getAccountId(), $subscriber->getNewaddress());
            $subscriber->hydrate($result);
            $subscriber->unsetNewAddress();
        }
        
        if(!empty($subscriber->getNewSubscriptions())){
            
            foreach($subscriber->getNewSubscriptions() as $newSubscription){
                $result = $this->updateSubscriberSubscription($subscriber->getAccountId(), $newSubscription);
            }
            
            //TODO fix dependency on last interation returning valid results.
            $subscriber->hydrate($result);
            $subscriber->unsetNewSubscriptions();
        }
        
        return $subscriber;
    }
    
    public function updateSubscriberAddress($subscriber_id, Address $address){
        $parts['path'] = 'api/v1.2/subscribers/'.$subscriber_id.'/addresses';
        
        $post['address_1'] = $address->getAddress1();
        $post['address_2'] = $address->getAddress2();
        $post['city'] = $address->getCity();
        $post['state'] = $address->getState();
        $post['zipcode'] = $address->getZipcode();
        $post['country'] = $address->getCountry();
        
        $response = $this->setApiEndpoint(build_url($this->api_url, $parts))
            ->sendRequest($this->getApiEndpoint(), $post);
        
        return $response->subscriber;
    }
    
    public function updateSubscriberSubscription($subscriber_id, NewSubscription $subscription){
        $parts['path'] = 'api/v1.2/subscribers/'.$subscriber_id.'/subscriptions';
        
        $post['publication_id'] = $subscription->getPublicationId();
        $post['issues_purchased'] = $subscription->getIssuesPurchased();
        $post['copies'] = $subscription->getCopies();
        $post['postage_id'] = $subscription->getPostageId();
        $post['promo_code'] = $subscription->getPromoCode();
        $post['giftgiver_account_id'] = $subscription->getGiftgiverAccountId();
        $post['never_expires'] = $subscription->getNeverExpires();
        $post['amount_paid'] = $subscription->getAmountPaid();
        $post['amount_due'] = $subscription->getAmountDue();
        $post['tax_amount'] = $subscription->getTaxAmount();
        $post['currency'] = $subscription->getCurrency();
        
        $response = $this->setApiEndpoint(build_url($this->api_url, $parts))
            ->sendRequest($this->getApiEndpoint(), $post);
        
        return $response->subscriber;
    }
    
    public function sendRequest($url, array $post=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if(!empty($post)){
            $post = json_encode($post);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // If not set, curl prints output to the browser
        curl_setopt($ch, CURLOPT_HEADER, false); // If set, curl returns headers as part of the data stream
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_user . ":" . $this->api_key);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // Turns off verification of the SSL certificate.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json"
        ));
        $response = curl_exec($ch); // Execute the API Call
        curl_close($ch);
        $response = json_decode($response);
        
        if(!empty($response->error)){
            throw new ApiError($response->error->message);
        }
        
        return $response;
    }
    
}
