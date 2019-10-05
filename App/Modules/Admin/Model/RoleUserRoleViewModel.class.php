<?php
class RoleUserRoleViewModel extends ViewModel {
	
	public $viewFields = array(
     'role_user'=>array('role_id'=>'rid','user_id'=>'uid'),
     'role'=>array('name','remark','_on'=>'role_user.role_id=role.id')
   );
}
?>