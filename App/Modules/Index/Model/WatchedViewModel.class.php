<?php
class WatchedViewModel extends ViewModel {
	
	public $viewFields = array(
     'video_recorder'=>array('id','uid','day','duration', '_on'=>'video.id=video_recorder.vid'),
     'video'=>array('id'=>'vid','title', 'content','url')
   );
}
?>