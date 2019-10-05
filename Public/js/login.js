function change_code(obj){
	$("#code").attr("src",verifyUrl+'/'+Math.random());
	return false;
}
//登录验证  1为空   2为错误
var validate={username:1,password:1,code:1}

$(function(){
	//验证用户名
	
	$("input[name='username']").blur(function(){

		var username = $("input[name='username']");
		if(username.val().trim()==''){
			$("#notice").text("用户名不能为空");
			return ;

		}
		$.post(ckusername,{username:username.val().trim()},function(stat){
			if(stat){
				validate.username=0;
				$("#notice").text('');
			}else{
				$("#notice").text("用户不存在");
			}

		})
	})
	//验证密码
	$("input[name='password']").blur(function(){
		var password = $("input[name='password']");
		var username=$("input[name='username']");
		if(username.val().trim()==''){
			return;
		}
		if(password.val().trim()==''){
			$("#notice").text("密码不能为空");
			return ;
		}
		$.post(ckpassword,{password:password.val().trim(),username:username.val().trim()},function(stat){
			if(stat==1){
				validate.password=0;
				$("#notice").text('');
			}else{
				$("#notice").text("密码错误");
			}

		})
	})
	//验证验证码
	$("input[name='code']").blur(function(){
		var code = $("input[name='code']");
		if(code.val().trim()==''){
			$("#notice").text("验证码不能为空");
			return ;
		}
		$.post(ckverify,{code:code.val().trim()},function(stat){
			if(stat==1){
				validate.code=0;
				$("#notice").text('');
			}else{
				$("#notice").text("验证码错误");
			}

		})
	})

	$("#login").click(function(){
		
      var password = $("input[name='password']").val().trim();
	  var username=$("input[name='username']").val().trim();
      var code = $("input[name='code']").val().trim();
      if(password&&username&&code){
         $.ajax({
                  type:'post',
                  dataType:'json',
                  url:loginurl,
                  data:{'password':password,'username':username,'code':code},
                  success:function(data){
                    if(data=='loginfail'){
                    	$("#notice").text("登录失败！");

                    }else if(data=='locked'){
                    	$("#notice").text("用户还没被激活！");

                    }else if(data=='codeerror'){
                    	$("#notice").text("验证码错啦！");

                    }else{
                    	location.href=data;

                    }
                  }
                });
      }else{
      	$("#notice").text("总是这样丢三落四，是不是表单没填完啊？？");
      }
	});
})

