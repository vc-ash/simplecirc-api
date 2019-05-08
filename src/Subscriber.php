<?php
namespace VcAsh;

use VcAsh\Traits\Hydrate;

/**
 *  name the subscriber's full name
 *  email the subscriber's email address
 *  company the subscriber's company
 *  TODO: prevent saving data that is not new. Set a flag
 */

class Subscriber extends BaseSubscriber {
    
    use Hydrate;
    
    protected $account_id;
    protected $renewal_link;
    protected $name;
    protected $first_name;
    protected $last_name;
    protected $email;
    protected $company;
    protected $address;
    protected $subscriptions;
    protected $new_address;
    protected $new_subscriptions;
    protected $new_name;
    protected $new_email;
    protected $new_company;
    protected $is_new = true;
    
    public function __construct($hydrate=array())
    {
        if(!empty($hydrate)){
            $this->hydrate($hydrate);
        }
        else{
            $this->setAddress();
            $this->setSubscriptions();
        }
    }
    
    public function setAccountId($value){
        $this->account_id = $value;
        $this->is_new = false;
        return $this;
    }
    
    public function setRenewalLink($value){
        $this->renewal_link = $value;
        return $this;
    }
    
    public function setName($value){
        $this->name = $value;
        return $this;
    }
    
    public function setFirstName($value){
        $this->first_name = $value;
        return $this;
    }
    
    public function setLastName($value){
        $this->last_name = $value;
        return $this;
    }
    
    public function setEmail($value){
        $this->email = $value;
        return $this;
    }
    
    public function setCompany($value){
        $this->company = $value;
        return $this;
    }
    
    public function setAddress($values=array()){
        $this->address = new Address($values);
        return $this;
    }
    
    public function setSubscriptions($subscriptions=array()){
        if(!empty($subscriptions)){
            $this->subscriptions = '';
            foreach($subscriptions as $subscription){
                $this->setSubscription($subscription);
            }
        }
        return $this;
    }
    
    public function setSubscription($values=array()){
        $this->subscriptions[] = new Subscription($values);
        return $this;
    }
    
    
    
    public function getAccountId(){
        return $this->account_id;
    }
    
    public function getRenewalLink(){
        return $this->renewal_link;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getFirstName(){
        return $this->first_name;
    }
    
    public function getLastName(){
        return $this->last_name;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getCompany(){
        return $this->company;
    }
    
    public function getAddress(){
        return $this->address;
    }
    
    public function getSubscriptions(){
        return $this->subscriptions;
    }
    
    /**
     *  Handle new/change address
     */
    public function setNewAddress($values=array()){
        $this->new_address = new Address($values);
        return $this;
    }
    
    public function getNewAddress($values=array()){
        return $this->new_address;
    }
    
    public function unsetNewAddress(){
        $this->new_address = '';
        //unset($this->new_address);
        return $this;
    }
    
    /**
     *  Handle Base Info Changes
     */
    public function setNewName($value){
        $this->new_name = $value;
        return $this;
    }
    
    public function setNewEmail($value){
        $this->new_email = $value;
        return $this;
    }
    
    public function setNewCompany($value){
        $this->new_company = $value;
        return $this;
    }
    
    public function getNewName(){
       return $this->new_name;
    }
    
    public function getNewEmail(){
        return $this->new_email;
    }
    
    public function getNewCompany(){
        return $this->new_company;
    }
    
    public function unsetNewName(){
       $this->new_name = '';
       return $this;
    }
    
    public function unsetNewEmail(){
        $this->new_email = '';
        return $this;
    }
    
    public function unsetNewCompany(){
        $this->new_company = '';
        return $this;
    }
    
    /**
     *  Handle new/renew subscriptions
     */
    public function addNewSubscriptions($subscriptions=array()){
        if(!empty($subscriptions)){
            foreach($subscriptions as $subscription){
                $this->addNewSubscription($subscription);
            }
        }
        return $this;
    }
    
    public function addNewSubscription($values=array()){
        $this->new_subscriptions[] = new NewSubscription($values);
        return $this;
    }
    
    public function getNewSubscriptions(){
        return $this->new_subscriptions;
    }
    
    public function unsetNewSubscriptions(){
        $this->new_subscriptions = '';
        return $this;
    }
    
    public function getIsNew(){
        return $this->is_new;
    }
    
}
