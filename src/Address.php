<?php
namespace SimpleCircApi;

use SimpleCircApi\Traits\Hydrate;

/**
 *  address_1 the subscriber's mailing address line 1
 *  address_2 the subscriber's mailing address line 2
 *  city the subscriber's mailing address city
 *  state the subscriber's mailing address state
 *  zipcode the subscriber's mailing address zipcode
 *  country the subscriber's mailing address country
 */

class Address {
    use Hydrate;
    
    protected $address_1;
    protected $address_2;
    protected $city;
    protected $state;
    protected $zipcode;
    protected $country;
    
    public function __construct($hydrate=array())
    {
        if(!empty($hydrate)){
            $this->hydrate($hydrate);
        }
    }
    
    /**
     *  Setter functions
     */
    public function setAddress1($value){
        $this->address_1 = $value;
        return $this;
    }
    
    public function setAddress2($value){
        $this->address_2 = $value;
        return $this;
    }
    
    public function setCity($value){
        $this->city = $value;
        return $this;
    }
    
    public function setState($value){
        $this->state = $value;
        return $this;
    }
    
    public function setZipcode($value){
        $this->zipcode = $value;
        return $this;
    }
    
    public function setCountry($value){
        $this->country = $value;
        return $this;
    }
    
    /**
     *  Getter functions
     */
    public function getAddress1(){
        return $this->address_1;
    }
    
    public function getAddress2(){
        return $this->address_2;
    }
    
    public function getCity(){
        return $this->city;
    }
    
    public function getState(){
        return $this->state;
    }
    
    public function getZipcode(){
        return $this->zipcode;
    }
    
    public function getCountry(){
        return $this->country;
    }
}
