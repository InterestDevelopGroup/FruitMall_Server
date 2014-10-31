
(function($) {
    $(document).ready(function() {
        //jquery.validate　提示语
        $.extend($.validator.messages, {
            required: "此填为必填项",
            remote: "该手机号码已注册",
            email: "请输入正确格式的电子邮件",
            url: "请输入合法的网址",
            date: "请输入合法的日期",
            dateISO: "请输入合法的日期 (ISO).",
            number: "请输入合法的数字",
            digits: "只能输入整数",
            creditcard: "请输入合法的信用卡号",
            equalTo: "请再次输入相同的值",
            accept: "请输入拥有合法后缀名的字符串",
            maxlength: jQuery.validator.format("请输入一个 长度最多是 {0} 的字符串"),
            minlength: jQuery.validator.format("请输入一个 长度最少是 {0} 的字符串"),
            rangelength: jQuery.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符串"),
            range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
            max: jQuery.validator.format("请输入一个最大为{0} 的值"),
            min: jQuery.validator.format("请输入一个最小为{0} 的值")
        });


        // Validate register form
        $('#reg_frm').validate({
        	rules:{
        		account:{
        			required: true
        		},
        		password:{
        			rangelength: [6, 8]
        		},
        		re_password: {
                    equalTo: '#password'
                },
                phone:{
                    required: true
                }
        	},
        	messages: {
                account: {
                    required: '请输入账号'
                },
                password: {
                    rangelength: '请输入{0}-{1}位的密码'
                },
                re_password: {
                    equalTo: '密码不一致'
                },
                phone:{
                    required: '请输入电话号码'
                }
            },
            submitHandler: function(form) {

                $(form).ajaxSubmit({
                    dataType: "json",
                    success: function(msg) {
                        if (msg.status == 1) {
                            alert(msg.result);
                            window.location.href='index.php?a=index&m=Index';
                        }else{
                            alert(msg.result);
                        }
                    }
                });

            }
        });

        // Validate login form
        $('#login_frm').validate({
            rules: {
                account: {
                    required: true
                },
                password:{
                    required:true
                }
            },
            messages: {
                account: {
                    required:'用户账号不能为空'
                },
                password:{
                    required:'用户密码不能为空'
                }
            },
            submitHandler: function(form) {

                $(form).ajaxSubmit({
                    dataType: "json",
                    success: function(msg) {
                        if(msg.status == 1) {
                            alert(msg.result);
                        	window.location.href='index.php?a=index&m=Index';
                        } else{
                            alert(msg.result);
                        }
                    }
                });
            }

        });

        //validate publish_form
        $('#publish_form').validate({
            rules: {
                tea_name: {
                    required: true
                }
            },
            messages: {
                tea_name: {
                    required:'茶叶名称不能为空'
                }
            }
        });

        //validate edit_form
        $('#edit_form').validate({
            rules: {
                phone: {
                    required: true
                },
                email:{
                    required:true
                }
            },
            messages: {
                phone: {
                    required:'电话号码不能为空'
                },
                email:{
                    required:'邮箱地址不能为空'
                }
            }
        });

        //create project form
        $('#create-project-form').validate({
            rules:{
                project_pic:{
                    required:true
                },
                name:{
                    required:true
                },
                project_time:{
                    required:true
                },
                region_name:{
                    required:true
                },
                sponsor:{
                    required:true
                },
                property:{
                    required:true
                },
                financial_require:{
                    required:true
                },
                introduce:{
                    required:true
                }
            },messages:{
                name:{
                    required:'请填写项目名称'
                },
                project_time:{
                    required:'请选择项目时间'
                },
                region_name:{
                    required:'请填写所在城市'
                },
                sponsor:{
                    required:'请填写项目发起人'
                },
                property:{
                    required:'请填写项目性质'
                },
                financial_require:{
                    required:'请填写资金需求'
                },
                introduce:{
                    required:'请填写项目介绍'
                }
            }
        });
 
    })
})(jQuery)


