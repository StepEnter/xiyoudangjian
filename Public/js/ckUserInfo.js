
$(function(){
	//alert("fseaf");
	//验证姓名
	$("input[name='name']").blur(function(){
		var name = $("input[name='name']");
		if(name.val().trim()==''){
			name.after("&nbsp;&nbsp&nbsp;<span class='error' style='color:red;'>姓名不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证性别
	$("input[name='sex']").blur(function(){
		var name = $("input[name='sex']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>性别不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证学号
	$("input[name='number']").blur(function(){
		var name = $("input[name='number']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>学号不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证入学年份
	$("input[name='entrance']").blur(function(){
		var name = $("input[name='entrance']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>入学年份不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证学院专业年级
	$("input[name='college']").blur(function(){
		var name = $("input[name='college']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>学院专业年级不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证生日
	$("input[name='birthday']").blur(function(){
		var name = $("input[name='birthday']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>生日不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证身份证号
	$("input[name='idcard']").blur(function(){
		var name = $("input[name='idcard']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>身份证号不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证家庭住址
	$("input[name='home']").blur(function(){
		var name = $("input[name='home']");
		alert(name.find("option").val().trim());
		if(name.find("option").val().trim()==''){
			name.after("<span class='error' style='color:red;'>请选择家庭住址</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	//验证手机号
	$("input[name='phone']").blur(function(){
		var name = $("input[name='phone']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>手机号不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})
	
	//验证Email
	$("input[name='email']").blur(function(){
		var name = $("input[name='email']");
		if(name.val().trim()==''){
			name.after("<span class='error' style='color:red;'>E-mail不能为空</span>");
			return ;
		}else{
			name.siblings().remove();return ;
		}
	})


})

