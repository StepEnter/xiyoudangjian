<?php
class QueryAction extends CommonAction {
	public function index() {
		$college = M('class')->where(array('flag'=>1))->select();
		$major = M('class')->where(array('flag'=>2))->select();
		$class = M('class')->where(array('flag'=>3))->select();
		$college = classAccess($college);
		$major = classAccess($major);
		$class = classAccess($class);
		//获取学院专业年级的信息
		$this->college = $college;
		$this->major = $major;
		$this->class = $class;
		$this->entranceRange = range(senior(), freshman());
        //p($major);die;
		$this->show();


		
 }
 public function query(){
 	   //查询条件
		$college = I('college',-1,'intval');
		$major = I('major',-1,'intval');
		$class = I('class',-1,'intval');
		$entrance = I('entrance',-1,'intval');
		$search = I('search');
		//如果没有选此限制条件则选择全部
		$thisyear = date('Y',time());
		$beforeyear = $thisyear - 4;
		if(!is_int($college)||$college==-1) $college = array('EXP','IS NOT NULL');
		if(!is_int($major)||$major==-1) $major = array('EXP','IS NOT NULL');
		if(!is_int($class)||$class==-1) $class = array('EXP','IS NOT NULL');
		if(!is_int($entrance)||$entrance==-1) $entrance = array('between',senior().','.freshman());
		$user_condition = array(
			'college' => $college,
			'major' => $major,
			'class' => $class,
			'entrance' => $entrance,
			'_logic' => 'AND'
			);
		//p($user_condition);die;



/*
		$collect_name = array();
		$collect_fields = M('collect_field')->order('c_order')->select();
		foreach ($collect_fields as $v) {
			$collect_name[] = $v['name'];
		}
		//将汇总字段名字在页面中显示
		$this->assign('collect_name',$collect_name);*/
		//分页列出汇总数据
		import('ORG.Util.Page');
		$count = M('userinfo')->where($user_condition)->count();
		$page = new Page($count,20);
	    
		$limit = $page->firstRow .','. $page->listRows;
	
		$userinfo = M('userinfo')->where($user_condition)->order('number')->limit($limit)->select();
        //p($userinfo);die;
        //加入权限控制

        $searchlist = array();
        foreach ($userinfo as $key => $value) {
        	//p(classAccess($value['class']));
        	if(!classAccess($value['class'])) continue;
        	 //将年级班级代码替换为名字
        	foreach ($value as $k => $v){
        		if($k=='college'||$k=='major'||$k=='class'){
        			$value[$k]=M('class')->where(array('id'=>$v))->getField('name');
        		}	
          }
         
          if($search){
					$kk = array_search($search, $value);
					if($kk=='number'||$kk=='name'||$kk=='username') 
						$searchlist[]=$value;
				}else{
					$searchlist[]=$value;
				}
        }
        /*$collect = array();
		foreach ($userinfo as $v) {
			$score = M('score')->where(array('uid'=>$v['uid']))->find();
			$collect[$v['number']] = array(
				'name'=> $v['name'] ,
				'number'=> $v['number'],
				'entrance' => $v['entrance'],
				'status' => $v['status'],
				'college' => $v['college'],
				'major' => $v['major'],
				'class' =>$v['class'],

				);
			foreach ($collect_fields as $co) {
				$collect[$v['number']][$co['id'].''] = operation($score,$co['operation']);	
			}
	}*/
	//p($collect);die;
	//$this->collect = $collect;
	//if($search) {
		$count = count($searchlist);
		$page = new Page($count,20);
		$this->userinfo = $searchlist;
		$this->page = $page->show();
	//}
	//else{
	 //$this->userinfo = $userinfo;
	// $this->page = $page->show();
	//}
	
	$this->display();

 }
 public function userpage(){
 	$uid = I('uid');
 	//荣誉相关
 	$activity = M('activity')->field('content',true)->where(array('uid'=>$uid,'type'=>1))->select();
 	$honor = M('activity')->field('content',true)->where(array('uid'=>$uid,'type'=>2))->select();
 	$feeling = M('activity')->field('content',true)->where(array('uid'=>$uid,'type'=>3))->select();
 	$this->activity = $activity;
 	$this->honor = $honor;
 	$this->feeling = $feeling;
 	//学习相关
 	$this->assess_item1 = getGridItems(1);
 	$this->assess_item2 = getGridItems(2);
 	
	$assess = D("AssessView")->where(array('uid'=>$uid,'type'=>1))->order('sort')->select();
	$assess = assess_merge($assess,1); //将成绩合并为三维数组，一维为time学期,二维为用户uid，三维为多个成绩
 	$this->item1 = $assess[1][$uid];
 	$this->item2 = $assess[2][$uid];
 	$this->item3 = $assess[3][$uid];
 	$this->item4 = $assess[4][$uid];
 	$this->item5 = $assess[5][$uid];
 	$this->item6 = $assess[6][$uid];
 	$this->item7 = $assess[7][$uid];
 	$this->item8 = $assess[8][$uid];
 	$thinkassess = D("AssessView")->where(array('uid'=>$uid,'type'=>2))->order('sort')->select();
 	//思想相关
 	$thinkassess = assess_merge($thinkassess,2);

 	$this->think = $thinkassess;
 	$this->user = M('userinfo')->where(array('uid'=>$uid))->field('number,name')->find();
 	$videosum = M('video_recorder')->where(array('uid'=>$uid))->sum('duration')/60.0;
	$this->video = sprintf("%.2f", $videosum); 
	//任务记录
	$this->task = D('CheckListView')->where(array('uid'=>$uid))->select();
 	//p($task);die;
 	//$this->item1 = M('')
 	//p($activity);die;
 	$this->show();
 }
}
?>