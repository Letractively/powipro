<?php

class AppModel extends Model {

	protected function valid ($id) {
		if (isset($id)) {
			$this->id = $id;
		}
		
		if (!isset($this->id)) {
			return false;
		}
		
		return true;
	}
}

?>
