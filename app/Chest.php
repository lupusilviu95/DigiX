<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chest extends Model
{
    public $id_cufar,$id_user,$capacity,$freeSlots,$description,$name,$createdat;
    public function getFreeSlots(){
    	return $this->capacity-$this->freeSlots;
    }
    
}
