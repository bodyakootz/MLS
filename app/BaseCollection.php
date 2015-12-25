<?php namespace App;

use Illuminate\Database\Eloquent\Collection;

class BaseCollection extends Collection {
	public function flate() {
		$items = $this->toArray($this->items);

		foreach ($items as $key => $val) {
			$items[$key] = (object) $this->getFlatArr($val);
		}

		return new static($items);
	}

	private function getFlatArr($arr) {
		while ($this->is_multidimensional($arr)) {
			$arr = $this->array_flate_single($arr);
		}
		return $arr;
	}

	private function is_multidimensional($arr) {
		foreach ($arr as $key=>$val) {
			if (is_array($val)) {
				return true;
			}
		}
		return false;
	}

	private function array_flate_single($arr) {
		$output = [];
		foreach ($arr as $key => $val) {
			if (is_array($val)) {
				$output = array_merge($output, $val);
			} else {
				$output[$key] = $val;
			}
		}
		return $output;
	}
}