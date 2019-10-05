<?php
class VideoAction extends CommonAction {
	public function index() {
		$this->video=M('video')->where(array('effective'=>1))->select();
		$this->show(); 
	}
	public function play(){
		$this->url = I('url');
		$this->vid = I('vid');
		$this->show();

	}
	public function recorder() { 
		$uid = $_SESSION['uid'];
		$vid = I('vid');
		$day = date("Y-m-d",time());
		$add = I('add');
		$type = I('type');
		$increase = true;
		$ok = 1;
		$recorder = M('video_recorder')->where(array('uid'=>$uid,'day'=>$day,'vid'=>$vid))->find();
		//p($recorder);die;
		if($recorder){
			$recorder['duration'] += $add;
			if(!M('video_recorder')->save($recorder))
				$ok = 0;

		}else{
			$recorder = array(
				'uid'=>$uid,
				'vid'=>$vid,
				'day'=>$day,
				'duration'=>$add
				);
			if(!M('video_recorder')->add($recorder))
				$ok = 0;
		}

		$this->ajaxReturn($ok,'json');
		
		
	}
	public function watched(){
		$recorder = M('video_recorder');
		$video_recorder = $recorder->where(array('uid'=>$_SESSION['uid']))->select();
		
		foreach ($video_recorder as $key => $value) {
			$video = M('video')->where(array('id'=>$value['vid']))->find();
		 	$video_recorder[$key]['title'] = $video['title'];
		 	$video_recorder[$key]['content'] = $video['content'];
		 	$video_recorder[$key]['url'] = $video['url'];
		 } 
		 //统计总的观看时长
		$sum = $recorder->where(array('uid'=>$_SESSION['uid']))->sum('duration')/60.0;
		$this->sum = sprintf("%.2f", $sum);
		$this->list = $video_recorder;
		$this->show();
	}

	public function mail(){
		if(sendMail('xupingxx@qq.com','党建系统测试邮件','党建系统测试邮件'))
			echo "发送成功";
		else
			echo "发送失败";
	}	
}
?>