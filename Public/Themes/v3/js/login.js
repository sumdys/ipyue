//(function () {
//    //var isLoginVal = $('#islogin').val() || false;
//    //if (isLoginVal && isLoginVal == 1) {
//    //    createLoginBox();
//    //    $('#loginPopup').show();
//    //    $.mask(0);
//    //}

//    if (!$.cookie('isLogin') || $.cookie('isLogin') == 0 || !$.cookie('WebMember')) {
//        console.log(123);
//        showLoginPopup();
//    } else {
//        checkLogin(false, function () {
//            console.log(321);
//            showLoginPopup();
//        });
//    }
//})();

//function checkLogin(loginBackFunc, noLoginbackFunc) {
//    $.ajax({
//        type: 'POST',
//        url: ' /member/json/CheckLogin',
//        success: function (reObj) {
//            //console.log(reObj);
//            if (reObj == 'noLogin') {
//                $.cookie('isLogin', 0, { path: '/' });
//                if (noLoginbackFunc) noLoginbackFunc();
               
//            } else {
//                $.cookie('isLogin', 1, { path: '/' });
//                if (loginBackFunc) loginBackFunc();
//            }
//        }
//    });
//}

function showLoginPopup(strTitle) {
    var $loginPopup = $('#loginPopup') || false;
    //console.log($loginPopup);

    if ($loginPopup.length == 0) createLoginBox(strTitle);
    $.mask(0);
    $('#loginPopup').show();
}

function createLoginBox(strTitle) {
    var CloseBtnTitel = strTitle || "非会员预订",
        html = '<div class="login-popup" id="loginPopup">' +
                    '<div class="popupbox">' +
                        '<h3>会员登录</h3>' +
                        '<div class="log-left">' +
                            '<input title="手机号码/邮箱" name="mobile" type="text" placeholder="请输入手机号码/邮箱" class="user" />' +
                            '<input title="密码" name="password" type="password"  placeholder="请输入密码" class="code" data-name="password" data-vdata="required,password" />' +
                            '<p>' +
                                '<a href="/member/member/foget" class="forget">忘记登录密码?</a>' +
                                '<a href="/member/member/reg">免费注册</a>' +
                            '</p>' +
                            '<input type="button" name="" id="btnEnter" value="登录" class="login-btn" />' +
                        '</div>' +
                        '<div class="nonviptips">' + CloseBtnTitel + '</div>' +
                        '<a class="nonvip" id="nonvip">直接下单</a>' +
                    '</div>' +
                '</div>';
    $('body').append(html);

    $('#loginPopup input').keyup(trimkeyup);
    $('#btnEnter').on('click', function () {
        
        var $nameInput = $('.log-left input[name="mobile"]'),
             nameVal = $nameInput.val();
        if ((/^[1][34578]\d{9}$/).test(nameVal) || (/^[1-9]\d*$/).test(nameVal) || nameVal.length == 11) {
            $nameInput.attr('data-vdata', 'required,mobi');
        } else {
            $nameInput.attr('data-vdata', 'required,email');
        }
        if (!$('#loginPopup').verifyForm()) return;
        $.ajax({
            type: 'POST',
            url: '/member/Json/Login',
            data: {
                name: nameVal,
                pass: $('input[name="password"]').val()
            },
            success: function (reObj) {
                reObj = $.parseJSON(reObj);
                alert(reObj.msg);
                if (reObj.ret == 1) {
                    $.cookie('isLogin', 'isLogin');
                    window.location.reload(); 
                    
                }
            }
        });
    });

    if ($('#isPanicBuy').length==0 || $('#isPanicBuy').val() != 1) {
        $('#nonvip').on('click', function () {
            $('#loginPopup').hide();
            $.mask(1);
        });
    } else {
        $('#nonvip').text('抢购产品不支持非会员预订').css({
            background: '#ccc',
            width: 280,
            margin: '95px 10px 0 0',
            'border-color':'#eee'
        });
    }
   
}
function keyLogin(event) {
    if (event.keyCode == 13)   //回车键的键值为13  
        $("#btnEnter").trigger('click'); //调用登录按钮的登录事件  
}
function trimkeyup(e) {
    lucene_objInput = $(this);
    if (e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 13) {
        var im = $.trim(lucene_objInput.val());
        lucene_objInput.val(im);
    }
}