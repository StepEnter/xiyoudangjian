<?php
class FieldsAction extends CommonAction {
	public function index(){
		$fields = M('assess_item')->select();
		foreach ($fields as $key => $value) {
			//p($value[type]);die;
			if($value[type]==1) $fields[$key][type]="学习相关";
			if($value[type]==2) $fields[$key][type]="思想相关";
			
		}
		//p($value[type]);die;
   		$this->fields = $fields;
		$this->display();
	}
	public function editField(){
		$field = M('assess_item')->where(array('id'=>I('fid')))->select();
		
		$this->field = $field[0];
		$this->display();
	}
	public function editFieldHandle(){
		if(M('assess_item')->where(array('id'=>I('fid')))->save($_POST))
			$this->ajaxReturn(1,'json');
		else
			$this->ajaxReturn(0,'json');
	}
	public function addField(){
		$this->display();

	}
	public function addFieldHandle(){
		if(M('assess_item')->add($_POST))
			$this->ajaxReturn(1,'json');
		else
			$this->ajaxReturn(0,'json');
	}
	public function delField(){
		$id = I('id',0,'intval');
			if(M('assess_item')->where(array('id'=>$id))->delete()){
				$this->ajaxReturn(1,"json");
			}else{
				$this->ajaxReturn(0,"json");
			}
	}
	public function fields() {
		$this->fields = M('fields')->select();
		$this->display();

	}
	public function fieldsHandle() {
		$id = I('id');
		$fname = I('fname');
		$sign = I('sign');
		$remark = I('remark');
		for($i=0;$i<20;$i++){
			$data = array(
				'id' =>$id[$i],
				'fname' =>$fname[$i],
				'sign' => $sign[$i],
				'remark' => $remark[$i]
				);
			M('fields')->save($data);

		}

	}

	public function collect (){
		$this->collect_field = M('collect_field')->order('c_order')->select();
		//p($collect_field);die;
		$this->show();

	}
	public function addCollect(){
		$this->show();
	}

	public function addCollectHandle() {
		if(M('collect_field')->add($_POST)){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}

	}

	public function editCollect() {
		$id = I('id');
		$this->collect_field = M('collect_field')->where(array('id'=>$id))->find();
		$this->show();
	}
	public function editCollectHandle() {
		if(M('collect_field')->save($_POST)){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}

	}
	public function delCollect(){
		$id = I('delid',0,'intval');
		if(M('collect_field')->where(array('id'=>$id))->delete()){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
}
?>