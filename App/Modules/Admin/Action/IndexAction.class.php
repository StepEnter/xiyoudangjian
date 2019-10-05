<?php
class IndexAction extends CommonAction {
	public function index(){
		
		$this->username = $_SESSION['username'];
		$this->display();
	}
	
}
?>