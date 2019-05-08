<?php
namespace VcAsh;

use VcAsh\Traits\Hydrate;

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
class BaseSubscription {
    use Hydrate;
    
    protected $publication_id;
    protected $never_expires = 0;
    protected $copies;
    
    public function __construct($hydrate=array())
    {
        if(!empty($hydrate)){
            $this->hydrate($hydrate);
        }
    }
    
    /**
     *  Setter functions
     */
    
    public function setPublicationId($value){
        $this->publication_id = $value;
        return $this;
    }
    
    public function setNeverExpires($value=0){
        $this->never_expires = $value;
        return $this;
    }
    
    public function setCopies($value){
        $this->copies = $value;
        return $this;
    }
    
    /**
     *  Getter functions
     */
    public function getPublicationId(){
        return $this->publication_id;
    }
    
    public function getNeverExpires(){
        return $this->never_expires;
    }
    
    public function getCopies(){
        return $this->copies;
    }
    
    
}
