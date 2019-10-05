<?php
class FilesAction extends CommonAction {
	public function docUpload(){
		$uid = $_SESSION['uid'];
		$task_id = I('task_id');
		$this->task = M('task')->where(array('id'=>$task_id))->find();
		$this->task_ok = M('task_ok')->where(array('uid'=>$uid,'task_id'=>$task_id))->find();
		$this->show();

	}
	public function docSave(){
		$uid = $_SESSION['uid'];
        $task_id = I('task_id');
        $file_id = array();
        $ok = 1;
		//p(date("Y-m-d H:i:s",time()));die;
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg','doc','docx', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Public/Uploads/';// 设置附件上传目录
		$upload->uploadReplace = true;
		if(!$upload->upload()) {// 上传错误提示错误信息
		    $ok = 0;//$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();
		}
		// 循环保存表单数据 包括附件数据
		foreach ($info as $v) {
			//查找是否存在相同文件
			$hash = $v['hash'];
			$existfile = M('files')->where(array('hash'=>$hash))->find();
			$existpath = $existfile['savepath'].$existfile['savename'];
			$uploadpath = $v['savepath'].$v['savename'];
			if($existpath&&file_exists($existpath)) {
				unlink($uploadpath);
				$v = $existfile;
			}

			$v['id'] = null;   //清除查询得到的对象的id，不然插不进去
			$v['uid'] = $_SESSION['uid'];
			$v['savetime'] = time();
			if($file_id[] = M("files")->add($v)){
				///数据库写入成功，不做动作
			}else{
				$ok = 0;
			}
		}
		//如果重新上传，将之前的文件置为无效
		M('user_files')->where(array('uid'=>$uid,'task_id'=>$task_id,'effective'=>1))->save(array('effective'=>0));
		//添加完成记录的信息
	
		foreach ($file_id as $v) {
			$user_files = array(
				'task_id' => $task_id,
				'uid' => $uid,
				'file_id' => $v,
				'task_id' => $task_id,
				'effective' => 1
			);
		  if(!M('user_files')->add($user_files))
		  	$ok = 0;
		}
		if(!M('task_ok')->where(array('uid'=>$uid,'task_id'=>$task_id))->find()){
			$task_ok = M('task_ok')->add(array('task_id'=>$task_id,'uid'=>$uid));
			if(!$task_ok) $ok = 0;
		}
		
			
		$this->ajaxReturn($ok,'json');

		
		
		
			
     }
 }
?>