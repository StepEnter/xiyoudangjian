<?php
class AssessAction extends CommonAction{
	
	public function addStudy(){
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
		
		$assess_item = getGridItems(1);//实参为1表示学习相关项目
		
		$tr = '';
		$tr = "{title : 'uid',dataIndex:'3'},{title : '学号',dataIndex:'0'},{title : '姓名',dataIndex:'1'},{title : '学期',dataIndex:'4'},";
		$titleIndex = ord('a');
		foreach ($assess_item as $key => $value) {
			
			$tr=$tr."{title : '".$value['remark']."', dataIndex :'".chr($titleIndex+$key)."',editor : {xtype : 'number',rules : {required : true}}},";
		
		}
		
		$this->tr=$tr;
		$this->show();
	}
	public function addThink(){
		//获取学院专业年级的信息
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
		
		$assess_item = getGridItems(2);//实参为1表示学习相关项目
		
		$tr = '';
		$tr = "{title : 'uid',dataIndex:'3'},{title : '学号',dataIndex:'0'},{title : '姓名',dataIndex:'1'},{title : '检查日期',dataIndex:'4'},";
		$titleIndex = ord('a');
		foreach ($assess_item as $key => $value) {
			
			$tr=$tr."{title : '".$value['remark']."', dataIndex :'".chr($titleIndex+$key)."',editor : {xtype : 'number',rules : {required : true}}},";
		
		}
		
		$this->tr=$tr;
		$this->show();
	}
	public function gridShow(){
		$type = I('type',1,'intval');
		$condition = array();
		if(I('search')==1){
			//查询条件
		$college = I('college',-1,'intval');
		$major = I('major',-1,'intval');
		$class = I('classes',-1,'intval');
		$entrance = I('entrance',-1,'intval');
		$time = I('time',-1,'intval');
		//p($task_comp);die;
		//如果没有选此限制条件则选择全部
		
		if(!is_int($college)||$college==-1) $college = array('EXP','IS NOT NULL');
		if(!is_int($major)||$major==-1) $major = array('EXP','IS NOT NULL');
		if(!is_int($class)||$class==-1) $class = array('EXP','IS NOT NULL');
		if(!is_int($entrance)||$entrance==-1) $entrance = array('between',senior().','.freshman());
		if(!is_int($time)||$time==-1) $time = array('EXP','IS NOT NULL');
          if($type==2){
          	$condition = array(
				'college'=>$college,
				'major'=>$major,
				'class'=>$class,
				'entrance'=>$entrance,
				'type'=>$type

				);

          }else{
          	$condition = array(
				'college'=>$college,
				'major'=>$major,
				'class'=>$class,
				'entrance'=>$entrance,
				'time'=>$time,
				'type'=>$type

				);
          }
			


		}else{
			$condition=array(
				'type'=>$type,
				);

		}
        $assess_item = getGridItems($type);
		$assess = D("AssessView")->where($condition)->order('sort')->select();
		//p($assess);die;
		$assessShow =array();
		//过滤没有权限管理的记录
		foreach ($assess as $key => $value) {
        	//p(classAccess($value['class']));
        	if(!classAccess($value['class'])) continue;
        	$assessShow[] = $value;
        }
		$assess = assess_merge($assessShow,$type); //将成绩合并为三维数组，一维为time学期,二维为用户uid，三维为多个成绩
       //p($assess);die;
		//按照json格式将$assess整合为json字符串
		$th = '';$re='';
		foreach ($assess as $key => $row) {
			//p($row);die;
			
			foreach ($row as $ke => $value) {
				$dataIndex = ord("a");
				if($type ==1)
					$th=$th.'{"0":"'.$value["number"].'","1":"'.$value["name"].'","3":"'.$value["uid"].'","4":"'.$value["time"].'",';
				else if($type ==2)
					$th=$th.'{"0":"'.$value["number"].'","1":"'.$value["name"].'","3":"'.$value["uid"].'","4":"'.$value["date"].'",';

				foreach ($assess_item as $k => $v) {
					$th=$th.'"'.chr($dataIndex+$k).'":"';
				    //p($th);die;
					foreach ($value as $item_id => $vall) {
						if($v["id"]==$item_id) $th=$th.$vall; //保证表头和数据的一致性

					}
					$th=$th.'",';
				}
				$th = substr($th, 0, -1);
				$th=$th.'},';
			}

			
		}
        $th = '{"result": true,"rows": ['.substr($th, 0, -1).'],"results" : 20}';
		echo $th;
		
	}
	//grid修改保存
	public function gridSaveHandle(){
		$records = $_POST['records'];
		$type  = $_POST['type'];
		$assess_item = getGridItems($type);
		$error = 0;
		//p($assess_item);die;
		foreach ($records as $key => $value) {
			$dataIndex = ord("a");
		    $save_ok=0;$add_ok=0;
		    //$value数组下标为3的值为uid，下标为4的值为学期次数time
		    $rowerror = 0;
			foreach ($assess_item as $k => $v) {
				$exist=M('assess')->where(array('uid'=>$value[3],'item_id'=>$v['id'],'time'=>$value[4]))->select();

				if($exist) {
					$save_ok = M('assess')->where(array('uid'=>$value[3],'item_id'=>$v['id'],'time'=>$value[4]))->save(array('value'=>$value[chr($dataIndex++)]));
			        if(!$save_ok) $rowerror++;
			    }
			    else {
			    	$add_ok = M('assess')->add(array('item_id'=>$v['id'],'uid'=>$value[3],'time'=>$value[4],'value'=>$value[chr($dataIndex++)]));
					if(!$add_ok) $rowerror++;
				}
			}
			if($rowerror>0) $error++;
		}
		if($error == 0) $this->ajaxReturn(1,'json');
		else $this->ajaxReturn(-$error,'json');//返回产生错误的条数

	}

	//excel导入代码
	function importHandle(){
		//type == 1为学习相关,==2为思想相关
		$type = I('type',1,'intval');
		//提示类型判断
		$ignore = 0;

		if($type==1) $startchar = 'D';//有用数据的开始列
		if($type==2) $startchar = 'D';
        if (!empty($_FILES)) {
            import('ORG.Net.UploadFile');
            $config=array(
                'allowExts'=>array('xlsx','xls'),
                'savePath'=>'./Public/Uploads/import/',
                'saveRule'=>'time',
            );
            $upload = new UploadFile($config);
            if (!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            } else {
                $info = $upload->getUploadFileInfo();
                
            }
        
            vendor("PHPExcel.PHPExcel");
                $file_name=$info[0]['savepath'].$info[0]['savename'];
                $objReader = PHPExcel_IOFactory::createReader('Excel5');
                $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow(); // 取得总行数
                $highestColumn = $sheet->getHighestColumn(); // 取得总列数
                if($highestColumn>'Z') $this->error("总列数不能超多26列");
                $items = array();$GridItems;
                $GridItems  = getGridItems($type);
                foreach ($GridItems as $key => $value) {
                	$items[] = $value['name'];
                }
                for($i=$startchar;$i<$highestColumn;$i++){

                	$a = $objPHPExcel->getActiveSheet()->getCell($i.'2')->getValue();
                	if(!in_array($a, $items)) $this->error("excel表头可能有错，请检查后重新上传哦！");

                }

                for($i=3;$i<=$highestRow;$i++)
                {   
                	$time = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
                	$number = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
                	$map['status']  = array('gt',0);
                	$map['number']  = $number;
                	$uid = M('userinfo')->where($map)->getField('uid');
                	$class = M('userinfo')->where($map)->getField('class');
                	if(!classAccess($class)) {$ignore=1;continue;} 
                	if(!$uid) {$ignore=1;continue;}
                	
                	for($j=$startchar;$j<=$highestColumn;$j++){
                		$item = $objPHPExcel->getActiveSheet()->getCell($j.'2')->getValue();
                		$key;
                		foreach ($GridItems as $k => $v) {
                			if(in_array($item,$v)) $key = $k;
                		}
                		//$key = array_search_re($item,$GridItems)[0][1];
                		$data['item_id'] = $GridItems[$key]['id'];
                		$data['uid'] = $uid;
                		if($type==2) $data['date'] = $time;
                		else $data['time'] = $time;
                		$data['value'] = $objPHPExcel->getActiveSheet()->getCell($j.$i)->getValue();
						//p($data);
						M('assess')->add($data);
                }         
                    //M('assess')->add($data);
         
                }
                 if($type == 1)$okbackurl = 'addStudy';if($type == 2)$okbackurl = 'addThink';
                 if($ignore ==1) $this->success('导入成功！并忽略不在你权限内的学生记录。',U('Admin/Assess/'.$okbackurl));
                 else $this->success('导入成功！');
        }else
            {
                $this->error("请选择上传的文件");
            }    
         
    }

}
?>