<?php namespace App;

use App\BaseCollection;
use Eloquent;

class BaseModel extends Eloquent {

	public function newCollection(array $models = array()) {
		return new BaseCollection($models);
	}
}