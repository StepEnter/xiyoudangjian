<?php
class NoticeAction extends CommonAction{
	function index(){
		$uid = $_SESSION['uid'];
		$this->status = M('status')->select();
        $this->notice = M('notice')->where(array('poster'=>$uid))->select();
		$this->show();
	}

	//修改业务流程
	public function editNotice(){
		$task_id = I("task_id");
		$task = M("notice")->where(array('id'=>$task_id))->find();
		$this->ajaxReturn($task,'json');
	} 
	
	//添加，修改

	public function noticeHandle(){
		//p($_POST);die;
		$id = I('id'); 
		$data = array(
			'id'=>$id,
			'title'=>I("title"),
			'status' =>I("task"),
			'time' =>time(),
			'poster'=>$_SESSION['uid'],
			'content' =>$_POST['editorValue']
			);
		if($id>0){ //如果真修改
			$ok = M("notice")->where(array('id'=>$id))->save($data);
			if($ok) $this->ajaxReturn(1,'json');
		}else{
			$ok = M("notice")->add($data);
			if($ok) $this->ajaxReturn(1,'json');
		}
		
		

	}
	public function delnoticeHandle(){
		$task_id = I("task_id");
		if(M("notice")->where(array('id'=>$task_id))->delete())
			$this->ajaxReturn(1,'json');
		else
			$this->ajaxReturn(0,'json');
	}
}
?>