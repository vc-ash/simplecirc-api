<?php
namespace VcAsh;

use VcAsh\Traits\Hydrate;

/**
 *  publication_id identifies the publication the subscription should be for, publication ids can be found on the API tab under account settings
 *  issues_purchased the number of issues the subscriber is getting
 *  copies the number of copies
 *  postage_id identifies the type of postage, postage ids can be found on the API tab under account settings
 *  promo_code promo code to be associated with the purchase
 *  giftgiver_account_id account_id of an existing subscriber if given as a gift
 *  never_expires 1 if the subscription should never expire, 0 otherwise
 *  amount_paid the amount paid
 *  amount_due the price of the purchase
 *  tax_amount the amount of tax paid
 *  currency the currency the purchase was denominated in
 */
class NewSubscription extends BaseSubscription {
    protected $publication_id;
    protected $issues_purchased;
    protected $copies;
    protected $postage_id;
    protected $promo_code;
    protected $giftgiver_account_id = null;
    protected $never_expires;
    protected $amount_paid;
    protected $amount_due;
    protected $tax_amount;
    protected $currency;
    
    public function __construct($hydrate=array())
    {
        if(!empty($hydrate)){
            $this->hydrate($hydrate);
        }
    }
    
    /**
     *  Setter functions
     */
    public function setIssuesPurchased($value){
        $this->issues_purchased = $value;
        return $this;
    }
    
    public function setPostageId($value){
        $this->postage_id = $value;
        return $this;
    }
    
    public function setPromoCode($value){
        $this->promo_code = $value;
        return $this;
    }
    
    public function setGiftgiverAccountId($value=null){
        $this->giftgiver_account_id = $value;
        return $this;
    }
    
    public function setAmountPaid($value){
        $this->amount_paid = $value;
        return $this;
    }
    
    public function setAmountDue($value){
        $this->amount_due = $value;
        return $this;
    }
    
    public function setTaxAmount($value){
        $this->tax_amount = $value;
        return $this;
    }
    
    public function setCurrency($value){
        $this->currency = $value;
        return $this;
    }
    
    
    /**
     *  Getter functions
     */
    public function getIssuesPurchased(){
        return $this->issues_purchased;
    }
    
    public function getPostageId(){
        return $this->postage_id;
    }
    
    public function getPromoCode(){
        return $this->promo_code;
    }
    
    public function getGiftgiverAccountId(){
        return $this->giftgiver_account_id;
    }
    
    public function getAmountPaid(){
        return $this->amount_paid;
    }
    
    public function getAmountDue(){
        return $this->amount_due;
    }
    
    public function getTaxAmount(){
        return $this->tax_amount;
    }
    
    public function getCurrency(){
        return $this->currency;
    }
    
    
}
