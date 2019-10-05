<?php
class VideoAction extends CommonAction {
	public function index(){
		$video = M('video')->select();
		foreach ($video as $k => $v) {
			$video[$k]['releaser'] = M('user')->where(array('id'=>$v['releaser_id']))->getField('username');
			$video[$k]['releaser_time'] = date("Y-m-d H:i:s",$v['releaser_time']);
		}
		$this->video = $video;
		$this->show();
	}
	public function addVideo(){
		$this->show();
	}
	public function addVideoHandle(){
		$video = $_POST;
		$video['releaser_id'] = $_SESSION['uid'];
		$video['releaser_time'] = time();
		if(M('video')->add($video)){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
	public function editVideo(){
		$id = I('id',0,'intval');
		$this->video = M('video')->where(array('id'=>$id))->find();
		$this->show();
	}
	public function editVideoHandle(){
		$video = $_POST;
		$video['releaser_id'] = $_SESSION['uid'];
		$video['releaser_time'] = time();
		if(M('video')->save($video)){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
	public function delVideo(){
		$id = I('delid',0,'intval');
		if(M('video')->where(array('id'=>$id))->delete()){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
}
?>