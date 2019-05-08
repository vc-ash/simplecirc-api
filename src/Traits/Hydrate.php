<?php
namespace VcAsh\Traits;

trait Hydrate {
    public function hydrate($data){
        foreach ($data as $attribute => $value){
            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
            if (is_callable(array($this, $method))){
                $this->$method($value);
            }
        }
        return $this;
    }
}
