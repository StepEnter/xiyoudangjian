<?php
Class RbacAction extends CommonAction {
	//用户列表
	public function index(){
		echo 11;

	}
	
	//节点列表
	public function node() {
		$field= array('id','pid','name','title','remark');
		$node= M('node')->order('sort')->field($field)->select();
		$this->node = node_merge($node);
		//p($node);
		$this->display();

	}
	//添加节点
	public function addNode(){
		$this->pid= I('pid',0,'intval');
		$this->level= I('level',1,'intval');

        switch($this->level){
        	case 1:$this->type ='应用';break;
        	case 2:$this->type ='控制器';break;
        	case 3:$this->type ='动作方法';break;

        }
		$this->display();

	}
	//添加节点表单处理
	public function addNodeHandle(){
		if(M('node')->add($_POST)){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}
	//编辑节点
	public function editNode(){
		$id= I('id',0,'intval');
		$this->id = $id;
		$this->pid = M('node')->where(array('id'=>$id))->getField('pid');
		$this->level= I('level',1,'intval');

        switch($this->level){
        	case 1:$this->type ='应用';break;
        	case 2:$this->type ='控制器';break;
        	case 3:$this->type ='动作方法';break;

        }
        $this->node = M('node')->where(array('id'=>$id))->find();
		$this->display();
	}
	//编辑节点表单处理
	public function editNodeHandle() {
		if(M('node')->save($_POST)){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}
	//删除节点
	public function delNode(){
		$id= I('id',0,'intval');
		if(M('node')->where(array('id'=>$id))->delete()){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}
	
	//添加角色
	public function addRole(){

		$this->display();

	}
	//角色列表
	public function role() {
		$this->role = M('role')->select();
		$this->display();

	}
	//添加角色表单处理
	public function addRoleHandle(){
		if(!IS_POST) halt('页面不存在');
		// M('role')->add($_POST);  //当表单名和字段名相同时可直接插入
		$data= array(
			'name'=>I('name'),
			'remark'=>I('remark'),
			'status'=>I('status')
			);

		if(M('role')->add($data)){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
		

	}
	
	//编辑角色页面
	public function editRole(){
		$id = I('rid',0,'intval');
		$this->role = M('role')->where(array('id'=>$id))->find();
		$this->display();
	}
    //编辑角色处理
    public function editRoleHandle(){
    	if(!IS_POST) halt('页面不存在');
    	$data = array(
    		'name'=>I('name'),
    		'remark'=>I('remark'),
    		'status'=>I('status')
    		);
    	if(M('role')->where(array('id'=>I('rid')))->save($data)){
    		$this->ajaxReturn(1,"json");
    	}else{
    		$this->ajaxReturn(0,"json");
    	}

    }
	//删除角色
	public function delRole(){
		$id = I('id',0,'intval');
		if(M('role')->where(array('id'=>$id))->delete()){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}

	}

	//配置权限
	public function access(){
		$rid = I('rid',0,'intval');
		$field = array('id','name','title','pid','level');

		$node = M('node')->order('sort')->field($field)->select();
		
        $access = M('access')->where(array('role_id'=>$rid))->getField('node_id', true);
        //对$node 数组进行重组为有层次的数组，调用function.php里面的node_merge
		$this->node= node_merge($node,$access);
		$this->rid = $rid;
		$this->display();

	}
	
	//修改配置权限
	public function setAccess(){
		$rid=I('rid',0,'intval');
		$db = M('access');
		$db->where(array('role_id' => $rid))->delete();
        $error=0;
		foreach ($_POST['access'] as $v) {
			$tmp=explode('_', $v);
			$data[]=array(
				'role_id'=>$rid,
				'node_id'=>$tmp[0],
				'level'=>$tmp[1]
				);
		}
		//这段可以用thinkPHP中addAll函数，减少代码量
			/*foreach ($data as $k=> $v) {
				if($db->add($v)) ;
				else $error=1;
			}
			
      if($error)$this->error('修改失败');
			else $this->success('修改成功',U('Admin/Rbac/role'));
*/
			//thinkphp中的addAll函数，和上面的作用相同
			if($db->addAll($data)) $this->success('修改成功',U('Admin/Rbac/role'));
			else $this->error('修改失败');
		
	}

	//用户管理
	public function user() {
		$search = I('search');
		$map = array();
			$user = D('UserRelation')->field('password',true)->relation(true)->select();
		
		$userlist=array();
		foreach ($user as $k => $v) {
			
			if(!empty($user[$k]['role'])){
				//p($user[$k]['status']);
				$user[$k]['name'] = M('userinfo')->where(array('uid'=>$v['id']))->getField('name');
				 $userlist[]=$user[$k];
			}
			
		    
		}
		if($search){
				$searchlist = array();
				foreach ($userlist as $key => $value) {
					$kk = array_search($search, $value);
					if($kk=='number'||$kk=='name'||$kk=='username') 
						$searchlist[]=$value;
				}
				$this->user = $searchlist;
				
		}else{
			$this->user = $userlist;
		}
		

		$this->show();

	}

	//添加用户
	public function addUser(){
		$this->role = M('role')->select();
		
		$this->display();

	}

	//添加用户表单处理
	public function addUserHandle(){
		$user = array(
			'username' => I('username'),
			'password' => I('password','','md5'),
			'email' => I('email'),	
			'lock'  => I('lock')
			);
		$uid = M('user')->add($user);
		if($uid>0) $userinfo = M('userinfo')->add(array('number'=>I('number'),'uid'=>$uid,'name'=>I('username')));
		$role_user=array();
		if($userinfo&&$uid){
			foreach ($_POST['role_id'] as $v) {
				$role_user[]=array(
					'role_id'=>$v,
					'user_id'=>$uid
					);
				}
				//p($role_user);die;
				if(M('role_user')->addAll($role_user))
					$this->ajaxReturn(1,'json');
				else
					$this->ajaxReturn(-1,'json');
				

		}else{
			$this->ajaxReturn(0,'json');
		}
	}

	public function delUser(){
		$uid = I('uid',0,'intval');
		if(M('user')->where(array('id'=>$uid))->delete()){
			M('userinfo')->where('uid='.$uid)->delete();
			M('role_user')->where('user_id='.$uid)->delete();
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}
	public function editUser(){
		$uid = I('uid');
		$user = M('user')->where(array('id'=>$uid))->select();
		$this->user = $user[0];
		//p($user);die;
		$this->role = M('role')->select();
		$this->hadRole = D('RoleUserRoleView')->where(array('uid'=>$uid))->select();

		$this->display();
	}
	public function editUserHandle(){
		$uid = I('uid');
		$user=array();
		if(I('password')==""){
			$user = array(
			'username' => I('username'),
			'email' => I('email'),	
			 'lock'  => I('lock')
			);
		}
		else{
			$user = array(
			'username' => I('username'),
			'password' => I('password','','md5'),
			'email' => I('email'),	
			 'lock'  => I('lock')
			);
		}
		//p($uid);die;
		$upUser = M('user')->where(array('id'=>$uid))->save($user);
		
		$role_user=array();
			foreach ($_POST['role_id'] as $v) {
				$role_user[]=array(
					'role_id'=>$v,
					'user_id'=>$uid
					);
				}
				$addRole = M('role_user')->addAll($role_user);
				if($addRole||$upUser)
					$this->ajaxReturn(1,'json');
				else if(!$addRole&&!$upUser)
					$this->ajaxReturn(0,'json');
				
				


	}
	public function delUserRole(){
		$rid=I("rid");
		$uid=I("uid");
		if(M('role_user')->where(array('role_id'=>$rid,'user_id'=>$uid))->delete()){
			$this->ajaxReturn(1,"json");
		}
		else{
			$this->ajaxReturn(0,"json");
		}
	}

    //班级年级管理
	public function classes(){
		$field= array('id','fid','name','order');
		$classes= M('class')->field($field)->select();
		//p($classes);die;
		$this->classes = class_merge($classes);
		//p($classes);die;
		$this->display();
		
	}

	//添加班级年级
	public function addClasses(){
		$this->fid= I('fid',0,'intval');
		$this->flag= I('flag',1,'intval');

        switch($this->flag){
        	case 1:$this->type ='学院';break;
        	case 2:$this->type ='专业';break;
        	case 3:$this->type ='班级';break;

        }
		$this->display();

	}
	//添加班级年级表单处理
	public function addClassesHandle(){
		if(M('class')->add($_POST)){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}
	//编辑班级年级
	public function editClasses(){
		$id= I('id',0,'intval');
		$this->id = $id;
		$this->fid = M('class')->where(array('id'=>$id))->getField('fid');
		$this->flag= I('flag',1,'intval');

        switch($this->flag){
        	case 1:$this->type ='学院';break;
        	case 2:$this->type ='专业';break;
        	case 3:$this->type ='班级';break;

        }
        $this->class = M('class')->where(array('id'=>$id))->find();
		$this->display();
	}
	//编辑班级年级表单处理
	public function editClassesHandle() {
		if(M('class')->save($_POST)){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}
	//删除班级年级
	public function delClasses(){
		$id= I('id',0,'intval');
		if(M('class')->where(array('id'=>$id))->delete()){
			$this->ajaxReturn(1,"json");
		}else{
			$this->ajaxReturn(0,"json");
		}
	}


	public function manage(){
		$rid = I('rid',0,'intval');
		$field = array('id','name','fid','flag');

		$node = M('class')->field($field)->select();
		//p($node);die;
        $access = M('manage')->where(array('role_id'=>$rid))->getField('class_id', true);
        //对$node 数组进行重组为有层次的数组，调用function.php里面的node_merge
		$this->node= class_merge($node,$access);
		$this->rid = $rid;
		$this->display();

	}


	//修改配置权限
	public function setManage(){
		$rid=I('rid',0,'intval');
		$db = M('manage');
		$db->where(array('role_id' => $rid))->delete();
        $error=0;
        //p($_POST['access']);die;
		foreach ($_POST['access'] as $v) {
			$tmp=explode('_', $v);
			$data[]=array(
				'role_id'=>$rid,
				'class_id'=>$tmp[0],
				'level'=>$tmp[1]
				);
		}
		/*//这段可以用thinkPHP中addAll函数，减少代码量
			foreach ($data as $k=> $v) {
				if($db->add($v)) ;
				else $error=1;
			}
			
      if($error)$this->error('修改失败');
			else $this->success('修改成功',U('Admin/Rbac/role'));

			//thinkphp中的addAll函数，和上面的作用相同*/
			
			if($db->addAll($data)) $this->success('修改成功',U('Admin/Rbac/role'));
			else $this->error('修改失败');
		
	}
	//业务流程管理
	public function task(){
		$showTask=M('task')->order(task_order)->select();
        $this->showTask = task_merge($showTask);

        $this->status = M("status")->order('id')->select();
        //p($showTask);die;

		$this->show();
	}
	//修改业务流程
	public function editTask(){
		$task_id = I("task_id");
		$task = M("task")->where(array('id'=>$task_id))->find();
		$this->ajaxReturn($task,'json');
	} 
	
	//添加，修改

	public function taskHandle(){
		//p($_POST);die;
		$id = I('id'); //id >0 说明此行为为修改
		$ftask = I('task');//父id
		$status=0;$effective;$task_url;$task_type=0;//初始化参数
		if($ftask == 0) $effective = 0;else $effective = 1;
		if(I("is_ok")==1) $task_url = "Index/Task/dotask";
		else {$task_url = "Index/Task/showTaskContent";$task_type = -1;}
		if(I("task_status")==0){
			$status = M('task')->where(array('id'=>$ftask))->getField('status');
		}else{
			$status = I("task_status");
		} 
		//p($status);die;
		$data = array(
			'id'=>$id,
			'title'=>I("title"),
			'effective'=>$effective,
			'status' =>$status,
			'fid' => $ftask,
			'task_order' =>I("order"),
			'task_url'=>$task_url,
			'task_type'=>$task_type,
			'releaser_id'=>$SESSION['uid'],
			'releaser_time'=>time(),
			'content' =>$_POST['editorValue']
			);
		if($id>0){ //如果是修改
			$ok = M("task")->where(array('id'=>$id))->save($data);
			if($ok) $this->ajaxReturn(1,'json');
		}else{
			$ok = M("task")->add($data);
			if($ok) $this->ajaxReturn(1,'json');
		}
		
		

	}
	public function deltask(){
		$task_id = I("task_id");
		if(M("task")->where(array('id'=>$task_id))->delete())
			$this->ajaxReturn(1,'json');
		else
			$this->ajaxReturn(0,'json');
	}
	public function editstatus(){
		$status_id = I("status_id");
		$status = M("status")->where(array('id'=>$status_id))->find();
		$this->ajaxReturn($status,'json');

	}
	public function statusHandle(){
		  $id = I('status_id');
		$data=array(
			'id'=>$id,
			'name'=>I('name'),
			'content'=>I('statusinfo')
			);

		if(M('status')->where(array('id'=>$id))->find()){
			$ok = M('status')->where(array('id'=>$id))->save($data);
			echo $ok;die;
			if($ok) $this->ajaxReturn(1,'json');

		}
		else{
			$ok = M('status')->add($data);
			if($ok) $this->ajaxReturn(1,'json');
		}
	}
	public function delstatus(){
		$status_id = I("status_id");
		if(M("status")->where(array('id'=>$status_id))->delete())
			$this->ajaxReturn(1,'json');
		else
			$this->ajaxReturn(0,'json');
	}


	//系统通用设置
	public function sysConf(){
		$conf = Config();//获取数据库中的配置文件

		$available = M('userinfo')->Distinct(true)->field('entrance')->select();
		$year = array();
		foreach ($available as $key => $value) {
			$year[] = $value['entrance'];
		}
        $this->year = $year;
		$this->checkemail = $conf['checkemail'];
		$this->basedsystime = $conf['basedsystime'];
		$this->freshman = $conf['freshman'];
		$this->mail_host = $conf['mail_host'];
		$this->mail_port = $conf['mail_port'];
		$this->mail_username = $conf['mail_username'];
		$this->mail_password = $conf['mail_password'];
		$this->show();

		/*if(I('type')=='edit'){
			
		}*/
	}

	public function sysConfHandle(){

		//p(I('mail_test'));die;
		if(I('type') == 'sendtestmail')
		{
			if(sendMail(I('mail_test'),'党建系统测试邮件','党建系统测试邮件,发送时间：'.date('Y-m-d H:i:s'))){
				$this->ajaxReturn('sended','json');

			}else{
				$this->ajaxReturn('sendfail','json');
			}

		}else if(I('type') == 'saveconf'){
			//写入配置
			try{
				if(I('checkemail')=='on'){
					Config('checkemail','1');
				}else{
					Config('checkemail','0');
				}

				if(I('basedsystime')=='on'){
					Config('basedsystime','1');
				}else{
					Config('basedsystime','0');
				    Config('freshman',I('freshman'));
				}
				
				
			    Config('mail_host',I('mail_host')) ;
				Config('mail_port',I('mail_port')) ;
				Config('mail_username',I('mail_username'));
				Config('mail_password',I('mail_password')) ;
			}catch(Exception $e){
				$this->ajaxReturn('configfail','json');die;
			}finally{
				$this->ajaxReturn('configed','json');
			}
			
		
		}
		
		
				
	}


}
?>