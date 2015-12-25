<?php namespace App;

use App\BaseModel;

class Feedback extends BaseModel {
    protected $guarded = [];
    protected $primaryKey = 'feedback_id';
    public $timestamps = false;

}