<?php
return array(
	/*'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' => __ROOT__.'/'.APP_NAME.'/Modules/'.GROUP_NAME.
		'/Tpl/Public',
		),*/

    
   //RBAC验证


    'NOT_AUTH_MODULE' => 'Index,Task',
    'NOT_AUTH_ACTION' => 'logout,addUserHandle,setAccess,userpage,importHandle,gridSaveHandle,gridShow,noticeHandle,delnoticeHandle,registerCheckHandle',
    

	'URL_HTML_SUFFIX' => ''
	);
?>