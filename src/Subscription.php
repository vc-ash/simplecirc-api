<?php
namespace SimpleCircApi;

use SimpleCircApi\Traits\Hydrate;

/**
 *  subscription_id unique identifier for the object
 *  publication_id identifies the publication that the subscription is for, publication_id can be found on the API tab under account settings
 *  publication_name the name of the publication
 *  status the status of the subscription (Active, Expired, On Hold, Cancelled, Bad Debt, Bad Address, Other)
 *  digital_status the status of the subscription as it relates to online access to a publication, this status will remain active while the subscription's last issue is still equal to the last issue published
 *  expiration_date the date the subscription expires or expired, if a subscription is active you should get a valid expiration_date, in the future, in the format of YYYY-MM-DD. The one exception to this is if the subscription "never expires". In this case the expiration_date will be blank and never_expires will be 1. If a subscription has expired and we know when they got their last issue then the expiration_date will be populated. If we don't have that information then the status will be expired but the expiration_date will be blank.
 *  never_expires 1 if the subscription never expires, 0 otherwise
 *  copies the number of copies
 *  issues_remaining the number of issues remaining
 *  giftgiver object representing the person who gifted the subscription
 */
class Subscription extends BaseSubscription {
    protected $subscription_id;
    protected $publication_id;
    protected $publication_name;
    protected $status;
    protected $digital_status;
    protected $expiration_date;
    protected $never_expires;
    protected $copies;
    protected $issues_remaining;
    protected $giftgiver;
    
    public function __construct($hydrate=array())
    {
        if(!empty($hydrate)){
            parent::__construct($hydrate);
        }
    }
    
    /**
     *  Setter functions
     */
    public function setSubscriptionId($value){
        $this->subscription_id = $value;
        return $this;
    }
    
    public function setPublicationName($value){
        $this->publication_name = $value;
        return $this;
    }
    
    public function setStatus($value){
        $this->status = $value;
        return $this;
    }
    
    public function setDigitalStatus($value){
        $this->digital_status = $value;
        return $this;
    }
    
    public function setExpirationDate($value){
        $this->expiration_date = $value;
        return $this;
    }
    
    public function setIssuesRemaining($value){
        $this->issues_remaining = $value;
        return $this;
    }
    
    public function setGiftgiver($values=array()){
        if(!empty($values)){
            $this->giftgiver = new GiftGiver($values);
        }
        return $this;
    }
    
    /**
     *  Getter functions
     */
    public function getSubscriptionId(){
        return $this->subscription_id;
    }
    
    public function getPublicationName(){
        return $this->publication_name;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function getDigitalStatus(){
        return $this->digital_status;
    }
    
    public function getExpirationDate(){
        return $this->expiration_date;
    }
    
    public function getIssuesRemaining(){
        return $this->issues_remaining;
    }
    
    public function getGiftgiver(){
        return $this->giftgiver;
    }
    
    
}
