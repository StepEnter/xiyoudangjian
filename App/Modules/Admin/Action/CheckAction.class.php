<?php
class CheckAction extends CommonAction {
	public function index (){
		$college = M('class')->where(array('flag'=>1))->select();
		$major = M('class')->where(array('flag'=>2))->select();
		$class = M('class')->where(array('flag'=>3))->select();
		$college = classAccess($college);
		$major = classAccess($major);
		$class = classAccess($class);
		//p($class);die;
		//获取学院专业年级的信息
		$this->college = $college;
		$this->major = $major;
		$this->class = $class;
		$this->entranceRange = range(senior(), freshman());

		$this->show();
	}
	public function checklist (){
		
		//查询条件
		$college = I('college',-1,'intval');
		$major = I('major',-1,'intval');
		$class = I('class',-1,'intval');
		$entrance = I('entrance',-1,'intval');
		$task_comp = I('task_comp',-1,'intval');
		//p($task_comp);die;
		//如果没有选此限制条件则选择全部
		
		if(!is_int($college)||$college==-1) $college = array('EXP','IS NOT NULL');
		if(!is_int($major)||$major==-1) $major = array('EXP','IS NOT NULL');
		if(!is_int($class)||$class==-1) $class = array('EXP','IS NOT NULL');
		if(!is_int($entrance)||$entrance==-1) $entrance = array('between',senior().','.freshman());
		if(!is_int($task_comp)||$task_comp==-1) $task_comp = array('EXP','IS NOT NULL');

        //p(D('CheckListView')->select());die;
		$check_condition = array(
			'college' => $college,
			'major' => $major,
			'class' => $class,
			'entrance' => $entrance,
			'task_comp' => $task_comp,
			'_logic' => 'AND'
			);

        //pageIndex	0

		//分页列出汇总数据
		//import('ORG.Util.Page');
		$count = D('CheckListView')->where($check_condition)->count();
        
		//$page = new Page($count,20);
		//$limit = $page->firstRow .','. $page->listRows;
		//用框架分页
		$limit = I('start',0,'intval').','.I('limit',0,'intval');
	    

		$check = D('CheckListView')->where($check_condition)->order('number')->limit($limit)->select();
        
        //p($check);die;
	
	
	//必须确保返回数据名称是双引号
        $json1='{"rows":[';
        $json2='';
        foreach ($check as $key => $value) {
        	if(!classAccess($value['class'])) continue;//筛除没有权限的人员
        	$value['name'] = "<a href='".U('Admin/Check/showContent')."?task_ok_id=".$value['task_ok_id']."'>".$value['name']."</a>";
        	$temp='{';
        	foreach ($value as $k => $v){
        		if($k=='college'||$k=='major'||$k=='class'){
        			$v=M('class')->where(array('id'=>$v))->getField('name');
        		}
        		if($k=='content') continue; //不太清楚为什么，返回数据中有content就不行，可能和bui冲突
        		else
        		$temp=$temp.'"'.$k.'":"'.$v.'",';
          }
          $json2=$json2.substr($temp, 0, -1).'},';
        	# code...
        }
        
        $json3 = '],"results": '.$count.'}';
        $json = $json1.substr($json2, 0, -1).$json3;
        
         echo $json;
	}

	//通用审核内容页
	public function showContent(){
		$id = I('task_ok_id');
		$task_ok = M('task_ok')->where(array('id'=>$id))->find();
		$task = M('task')->where(array('id'=>$task_ok['task_id']))->find();
		$this->task= $task;
		$this->task_ok= $task_ok;
		$this->show();
	}

	public function check(){
		$this->uid = I('uid');
		$this->task_id = I('task_id');
		$this->check_url = I('check_url');
		$this->show();

	}
	public function checkHandle(){
		$ids = I('ids');
		$check_result = I('check_result');
		$check_result = I('check_result',0,'intval');

		$data = array(
			'checker_id' => $_SESSION['uid'],
			'check_time' => time(),
			'check_result' => $check_result ,
			'check_feedback' => I('check_feedback')
			);
		//task_comp  0为待审核，1为审核未通过，2为审核通过
		if($check_result == 1)  $data['task_comp'] = 1;
		if($check_result > 1) $data['task_comp'] = 2;
		$check_ok = 1;
        foreach ($ids as $key => $value) {
        	if(!M('task_ok')->where(array('id'=>$value,'task_comp'=>array('in','0,1'),'_logic'=>'AND'))->save($data))
        		$check_ok = 0;
        }
		
		if($check_ok){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
		
	}
	public function  checkshowdetail(){
		$ids = I('ids');
		$con = M('task_ok')->where(array('id'=>$ids[0]))->getField('content');
		$this->ajaxReturn($con,'json');

	}
    public function checkCancel(){
    	$ids = I('ids');
    	$data = array(
    		'task_comp'=>1,
    		'check_result'=>1,
    		'check_feedback'=>''
    		);
    	$check_ok = 1;
    	foreach ($ids as $key => $value) {
        	if(!M('task_ok')->where(array('id'=>$value,'task_comp'=>2,'_logic'=>'AND'))->save($data))
        		$check_ok = 0;
        }
		
		if($check_ok){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}

    }
    public function checkback() {
    	
       I("check_result");
       I("check_feedback");
       p(I("ids"));


    }


	public function checkUserinfo() {
		$uid = I('uid');
		$task_id = I('task_id');
		$checkuserinfo = M('userinfo')->where(array('uid'=>$uid))->find();
		$checkuserinfo['college'] = M('class')->where(array('id'=>$checkuserinfo['college']))->getField('name');
		$checkuserinfo['major'] = M('class')->where(array('id'=>$checkuserinfo['major']))->getField('name');
        $checkuserinfo['class'] = M('class')->where(array('id'=>$checkuserinfo['class']))->getField('name');
        $this->checkuserinfo = $checkuserinfo;
		$this->show();
	}
	public function checkfile(){
		$uid = I('uid');
		$task_id = I('task_id');
		$file_id = M('user_files')->where(array('uid'=>$uid,'task_id'=>$task_id,'effective'=>1))->getField('file_id',true);
	    $files = array();
	    foreach ($file_id as  $v) {
	    	$files[] = M('files')->where(array('id'=>$v))->find();
	    }
	    $this->files = $files;
	    $this->show();
	}
	public function activity(){
		$college = M('class')->where(array('flag'=>1))->select();
		$major = M('class')->where(array('flag'=>2))->select();
		$class = M('class')->where(array('flag'=>3))->select();
		$college = classAccess($college);//是否有class权限
		$major = classAccess($major);
		$class = classAccess($class);
		//p($class);die;
		//获取学院专业年级的信息
		$this->college = $college;
		$this->major = $major;
		$this->class = $class;
		$this->entranceRange = range(senior(), freshman());

		$this->show();
	}
	public function activityList(){

		//查询条件
		$college = I('college',-1,'intval');
		$major = I('major',-1,'intval');
		$class = I('class',-1,'intval');
		$entrance = I('entrance',-1,'intval');
		$check_comp = I('check_comp',0,'intval');
		//p($task_comp);die;
		//如果没有选此限制条件则选择全部
		
		if(!is_int($college)||$college==-1) $college = array('EXP','IS NOT NULL');
		if(!is_int($major)||$major==-1) $major = array('EXP','IS NOT NULL');
		if(!is_int($class)||$class==-1) $class = array('EXP','IS NOT NULL');
		if(!is_int($entrance)||$entrance==-1) $entrance = array('between',senior().','.freshman());
		if(!is_int($check_comp)||$check_comp==-1) $check_comp = array('EXP','IS NOT NULL');


		$check_condition = array(
			'college' => $college,
			'major' => $major,
			'class' => $class,
			'entrance' => $entrance,
			'check_comp' => $check_comp,
			'_logic' => 'AND'
			);
		//分页列出汇总数据
		import('ORG.Util.Page');
		$count = M('activitylistview')->where($check_condition)->count();
		//p($count);
		$page = new Page($count,20);
		$limit = $page->firstRow .','. $page->listRows;

        //p($limit);

		$check = D('activitylistview')->where($check_condition)->order('number')->limit($limit)->select();
        //p($check);//die;
        $checkshow = array();
        foreach ($check as $key => $value) {
        	//p(classAccess($value['class']));
        	if(!classAccess($value['class'])) continue;
        	$checkshow[] = $value;
        }
        //p($checkshow);
        $count = count($checkshow);
		$page = new Page($count,20);
		$this->check = $checkshow;
		$this->page = $page->show();
		$this->display();
	}
   ///活动验证页
	public function activitycheck() {
		$this->uid = I('uid');
		$aid = I('aid');
		$this->activity = M('activity')->where('id='.$aid)->find();
		$this->show();
	}
	//活动验证处理页
	public function activitycheckHandle(){
		$uid = I('uid');
		$aid = I('aid');
		$check_result = I('check_result',0,'intval');

		$data = array(
			'checker_id' => $_SESSION['uid'],
			'check_time' => time(),
			'check_result' => $check_result ,
			'check_feedback' => I('check_feedback')
			);
		//task_comp  0为待审核，1为审核未通过，2为审核通过
		if($check_result == 1)  $data['check_comp'] = 1;
		if($check_result > 1) $data['check_comp'] = 2;
		if(M('activity')->where(array('id'=>$aid,'check_comp'=>0,'_logic'=>'AND'))->save($data)){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
	public function registerCheck(){
		$this->display();
	}


	public function registerList(){
		$check_condition1;
		if(!M('user')->where(array('id'=>$_SESSION['uid']))->getField('username')==C('RBAC_SUPERADMIN'))
		{
			$ma = D('UserManageView')->where(array('user_id'=>$_SESSION['uid']))->select();
			$manage = array();
			foreach ($ma as $key => $value) {

				$manage[] = $value['class_id'];
			}
		
		//p($manage);die;
		$check_condition1 = array(
			'college' => array('in',$manage),
			'major' => array('in',$manage),
			'class' => array('in',$manage),
			'_logic'=> 'OR',
			'status'=>0,
			'_logic'=>'AND'
			);
		}else{
			$check_condition1 = array(
			'status'=>0,
			'_logic'=>'AND'
			);
		}

		$count = M('userinfo')->where($check_condition1)->count();
        //p($check_condition);die;
		$limit = I('start',0,'intval').','.I('limit',0,'intval');
	    

		$check = M('userinfo')->where($check_condition1)->order('number')->limit($limit)->select();
          $checkshow = array();
         foreach ($check as $key => $value) {
        	//p(classAccess($value['class']));
        	if(!classAccess($value['class'])) continue;
        	$checkshow[] = $value;
           }
         $check =  $checkshow; 
	
	
	//必须确保返回数据名称是双引号
        $json1='{"rows":[';
        $json2='';
        foreach ($check as $key => $value) {
        	$temp='{';
        	foreach ($value as $k => $v){
        		if($k=='college'||$k=='major'||$k=='class'){
        			$v=M('class')->where(array('id'=>$v))->getField('name');
        		}
        		$temp=$temp.'"'.$k.'":"'.$v.'",';
          }
          $json2=$json2.substr($temp, 0, -1).'},';
        	# code...
        }
        
        $json3 = '],"results": '.$count.'}';
        $json = $json1.substr($json2, 0, -1).$json3;
        
         echo $json;
		
	}

	public function registerCheckHandle(){
		$ids = I('ids');
		
		$check_ok = 1;
		foreach ($ids as $key => $value) {
			$userinfo = M('userinfo');
			$role_user = M('role_user');
			$userinfo->startTrans();
			$role_user->startTrans();
			M('userinfo')->where(array('uid'=>$value))->save(array('status'=>1));
			$student = M("role")->where(array('name'=>'student'))->getField('id');
			$up = M('role_user')->add(array('user_id'=>$value,'role_id'=>$student));
			if(!$up){
				$userinfo->rollback();
				$role_user->rollback();
				$check_ok = 0;
			}else{
				$userinfo->commit();
				$role_user->commit();
			}
		}
		if($check_ok){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
	//删除注册记录
	public function delCheckHandle(){
		$ids = I('ids');
		
		$check_ok1 = 0 ;$check_ok2 = 0 ;
		foreach ($ids as $key => $value) {
			$check_ok1 = M('userinfo')->where(array('uid'=>$value))->delete();
			$check_ok2 = M('user')->where(array('id'=>$value))->delete();

		}
		if($check_ok1&&$check_ok2){
			$this->ajaxReturn(1,'json');
		}else{
			$this->ajaxReturn(0,'json');
		}
	}
}
?>