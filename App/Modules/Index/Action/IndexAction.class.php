<?php
class IndexAction extends CommonAction {
    public function index(){
    	$uid = $_SESSION['uid'];
    	$status = M('userinfo')->where(array('uid'=>$uid))->getField('status');
        $this->status = M('status')->where('id='.$status)->select();
    	$task=M('task')->where(array('fid'=>0))->order(task_order)->select();
    	$current_id = $task[$status-1][id];//当前任务组的id号
    	$this->current_task = M('task')->where(array('fid'=>$current_id))->order(task_order)->select();
    	$showTask=M('task')->order(task_order)->select();
        $this->showTask = task_merge($showTask);
        //通知公告
        //
        $notice = M("notice")->where(array('status'=>array('in','-1,'.$status)))->select();
        //p($notice);die;
        $is_in=array();
        $notice_list=array();

        foreach ($notice as $key => $value) {
            unset($is_in);
            if($value['poster'] == 1) {$notice_list[]=$value;continue;}
            else{
                $manage = D('UserManageView')->where(array('user_id'=>$value['poster']))->select();
                foreach ($manage as $k => $v) {
                    $is_in[] = $v['class_id'] ;
                }

                $class = M('userinfo')->where(array('uid'=>$uid))->getField('class');
                if(in_array($class, $is_in)) $notice_list[]=$value;
            }
            
        }
        $this->notice = $notice_list;

        

        //if($notice.)

        //个人汇总--------
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
	    $this->show(); 
    }

     //通知公告展示
    public function showNotice(){
        $notice_id = I("notice_id");
        $this->notice = M("notice")->where(array('id'=> $notice_id))->find();
        $this->show();
    }
    public function showtask(){
        $task_id = I("task_id");
        $this->task = M("task")->where(array('id'=> $task_id))->find();
        $this->show();
    }


    //任务时间线
    public function taskLine() {
        $uid = $_SESSION['uid'];
        $this->status = M('userinfo')->where(array('uid'=>$uid))->getField('status');
        $this->taskline = M('task_line')->where(array('uid'=>$uid))->order('time desc')->select();
    	//p($taskline);die;
        $this->show();
    }
    //打印页面
    public function printpage(){
        $uid = $_SESSION['uid'];
        //个人汇总--------
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
        $userinfo = M('userinfo')->where(array('uid'=>$uid))->find();
        
        foreach ($userinfo as $key => $value) {
            if($key=='college'||$key=='major'||$key=='class')
                $userinfo[$key] = M('class')->where(array('id'=>$value))->getField('name');
            if($key=='sex') {if($value = 0) $userinfo[$key] = '男';if($value = 1) $userinfo[$key] = '男';}
            if($key=='status')
                $userinfo[$key] = M('status')->where(array('id'=>$value))->getField('name');    
        }

        $this->user = $userinfo;
        $videosum = M('video_recorder')->where(array('uid'=>$uid))->sum('duration')/60.0;
        $this->video = sprintf("%.2f", $videosum); 
        //任务记录
        $this->task = D('CheckListView')->where(array('uid'=>$uid))->select();
        $this->show();

    }
}