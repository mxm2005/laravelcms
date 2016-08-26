@extends('layouts.login')
@section('content')
	<!--SIGN UP-->
	<h1>账号注册中心界面</h1>
	<div class="login-form">
		<!--<div class="close"> </div>-->
			<div class="head-info">
				<label class="lbl-1"> </label>
				<label class="lbl-2"> </label>
				<label class="lbl-3"> </label>
			</div>
			<div class="clear"> </div>
			<div class="avtar">
				<img src="{{asset('/module/login')}}/images/avtar.png" />
			</div>
			<script type="text/javascript">
			function postcheck_frm()
			{
				frm = document.do_form;
			    
			    var email = frm.email;
			    var name = frm.name;
			    var userpwd = frm.userpwd;
			    var reuserpwd = frm.reuserpwd;
			    
			    if(email.value=="")
			    {
			        layer.alert("请输入注册邮箱!",{icon: 7,skin: 'layer-ext-moon'});
			        return false;
			    }
			    if(name.value=="")
			    {
			        layer.alert("请输入用户名称!",{icon: 7,skin: 'layer-ext-moon'});
			        return false;
			    }
			    if(userpwd.value=="")
			    {
			        layer.alert("请您填写注册密码!",{icon: 7,skin: 'layer-ext-moon'});
			        return false;
			    }
			    if(userpwd.value.length<6)
			    {
			        layer.alert("密码长度需6位以上!",{icon: 7,skin: 'layer-ext-moon'});
			        return false;
			    }
			    if(reuserpwd.value=="")
			    {
			        layer.alert("请您填写确认密码!",{icon: 7,skin: 'layer-ext-moon'});
			        return false;
			    }
			    if(userpwd.value && reuserpwd.value && userpwd.value!=reuserpwd.value)
			    {
			        layer.alert("您填写注册密码与确认密码不一致!",{icon: 7,skin: 'layer-ext-moon'});
			        return false;
			    }

			    return true;
			}
			$(function ()
			 {   
		       $('#do_action').click(function()
		         {
                    if(postcheck_frm())
                    {
                        var loadi;
                        $.ajax({
                            type:"POST",
                            url:"{{ url('/register') }}",
                            data:$('#do_form').serialize(),
                            dataType:'json',
                            beforeSend: function (){
                                loadi=layer.load("检测中...");
                            },
                            success:function(msg)
                            {
                                layer.close(loadi);
                                if(msg.success==1)
                                {
                                    var msg_data=msg.data;
                                    var curl=msg_data.curl;
                                    layermsg_s(msg.info,curl);
                                }
                                else
                                {
                                    layermsg_error(msg.info);
                                }
                            },
                            error:function()
                            {
                                layer.close(loadi);
                                var msgs="响应请求失败！";
                                layer.msg(msgs);
                            }     
                        }) 
                    }
		      });
			 });
			</script>
			<form class="form-horizontal" role="form" id="do_form" name="do_form" method="POST" action="{{ url('/register') }}">
			<input type="hidden" name="type" value="2">
			{{ csrf_field() }}
				<input type="text" class="text" id="email" name="email" value="" placeholder="：" style="width:60%;padding-left:5em;background:url('{{asset('/module/login')}}/images/email.png') no-repeat left center;">
				<input type="text" class="text" name="name" value="" placeholder="：" style="margin-top:0;width:60%;padding-left:5em;background:url('{{asset('/module/login')}}/images/name.png') no-repeat left center;">
				<input id="password" type="password"  name="userpwd" placeholder="："  style="margin-bottom:0;width:60%;padding-left:5em;background:url('{{asset('/module/login')}}/images/pwd.png') no-repeat left center;">
				<input id="password" type="password"  name="reuserpwd" placeholder="：" style="width:60%;padding-left:5em;background:url('{{asset('/module/login')}}/images/repwd.png') no-repeat left center;">
				<div class="signin">
					<input type="button" id="do_action" value="注册/Register" >
				</div>
			</form>
	</div>
@endsection