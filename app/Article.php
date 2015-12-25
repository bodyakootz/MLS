<?php namespace App;

use App\BaseModel;

class Article extends BaseModel {
    protected $guarded = [];
    protected $primaryKey = 'article_id';
    public $timestamps = false;

}