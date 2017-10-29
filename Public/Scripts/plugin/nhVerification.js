/*
 *
 * jQuery.nhVerification for PC v1.0
 * http://www.nanhutravel.com
 *
 * Author Eeqlee
 * Create 2015-12-09
 *
 * 使用例子: <input type="password" name="password" title="密码" data-vdata="required,password"/> //必填+密码规则
 *
 * Copyright (c) 2015-2016 www.nanhutravel.com.
 *
 */
(function ($) {

    $.fn.verifyForm = function (options) {
        if (options ? options.initialize : false) {
            this.each(function () {
                var settings = {
                    targetBox: $(this),
                    initialize : true
                },
                options = $.extend(settings, options);
                $.verifyForm.set(options);
            });
        } else {
            var res = true;
            this.each(function () {
                var settings = {
                    targetBox: $(this),
                    initialize: false
                },
                    options = $.extend(settings, options);
                res = $.verifyForm.set(options);
            });
            return res;
        }
    };

    $.verifyForm = {
        settings: $.extend({
            targetBox: '#form',
            vObjAttr: 'data-vdata',
            isOrder: false,
            res: false
        }, {}),
        set: function (options) {
            var thisFun = this;
            thisFun.settings = $.extend(thisFun.settings, options);
            thisFun.start();
            return thisFun.settings.res;
        },
        initialize: function () {
            var thisFun = this,
                vObjAttr = thisFun.settings.vObjAttr,
                $targetBox = $(thisFun.settings.targetBox),
                $targets = $targetBox.find('input[' + vObjAttr + ']:visible,textarea[' + vObjAttr + ']:visible,select[' + vObjAttr + ']:visible');
            thisFun.settings = $.extend(thisFun.settings, {
                targets: $targets,
                alertText: ''
            });
            thisFun.settings.targets.filter(':not(.datebox)').off('focusout').on('focusout', function () {
                thisFun.checkItem($(this));
            });
            $targetBox.addClass('verifying');
            if(!thisFun.settings.initialize) thisFun.start();
        },
        start: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $targets = $targetBox.find('input,textarea,select');
            if ($targetBox.length != 0 && $targets.length != 0) {//检查必要元素是否存在
                if (!$targetBox.hasClass('verifying')) {//是否已初始化
                    thisFun.initialize();
                } else {//过检, 开始校验表单
                    return (thisFun.startVerify());
                }
            } else {
                console.log('没找到目标对象,请检查传参是否正确.');
                return false;
            }
        },
        startVerify: function () {
            var thisFun = this;
            $targetBox = $(thisFun.settings.targetBox),
                $targets = $(thisFun.settings.targets);

            $targets.each(function () {
                var $item = $(this);
                thisFun.checkItem($item);
                return thisFun.settings.res;
            });
            $targetBox.removeClass('verifying');
        },
        checkItem: function($item){
            var thisFun = this,
                vObjAttr = thisFun.settings.vObjAttr,
                itemNorm = $item.attr(vObjAttr);

            if (!itemNorm || itemNorm.length == 0) return;
            itemNorm = itemNorm.split(',');

            $.each(itemNorm, function (i, j) {
                //console.log('r:'+thisFun.verify($item, j));
                if (!thisFun.verify($item, j)) {
                    //提示信息Begin - thisFun.settings.alertText 为提示文本
                    alert(thisFun.settings.alertText);//这里简单粗暴直接ALERT出来, 以后再添加POP和INPUT边框变色等效果.
                    $item.trigger('mouseenter').css('border-color', '#d91d28');
                    //提示信息End
                    if ($('.mask:visible').length == 0) {
                        $('html,body').animate({
                            scrollTop: $item.offset().top - 100
                        }, 300);
                    }
                    thisFun.settings.res = false;
                    return false;
                } else {
                    $item.css('border-color', '#dedede');
                    thisFun.settings.res = true;
                }
            });
        },
        verify: function ($item, norm) {
            var thisFun = this,
                val = $.trim($item.val()),
                valarr = val.split(" ");
            thisFun.settings.alertText = '请填写合法的' + $item.attr('title');

            //南湖PC官网订单页特别验证
            if (thisFun.settings.isOrder) {
                return thisFun.orderVerify();
            }

            //检验规则
            if (($item.attr('required') || norm == 'required') && (!val || val == '' || ($item.is('select') && val == 0))) {//必填
                thisFun.settings.alertText = $item.attr('title') + '未' + ($item.is('select') ? '选择' : '填写') + '，请补充完整';
                return false;
            } else if (norm == 'enText') {//英文字符(包含大小写),允许空格
                var a = "";
                for (var i = 0; i < valarr.length; i++) {//删除行内空格 by lixx
                    a += valarr[i];
                }
                if (!(/^[A-Za-z]+$/).test(a)) {
                    thisFun.settings.alertText = $item.attr('title') + '只能填写英文字符';
                    return false;
                }
            } else if (norm == 'integerP' && !(/^[1-9]\d*$/).test(val)) {//数字-正整数
                thisFun.settings.alertText = $item.attr('title') + '只能填写数字';
                return false;
            } else if (norm == 'integerN' && !(/^-[1-9]\d*$/).test(val)) {//数字-负整数
                thisFun.settings.alertText = $item.attr('title') + '只能填写负数';
                return false;
            } else if (norm == 'email' && val != '' && !(/[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/).test(val)) {//EMAIL
                return false;
            } else if (norm == 'mobi' && (val != '' && !(/^[1][34578]\d{9}$/).test(val) || !(/^[1-9]\d*$/).test(val) || val.length != 11)) {//手机
                return false;
            } else if (norm == 'phone' && !(/[0-9-()（）]{7,18}/).test(val)) {//固定电话
                return false;
            } else if (norm == 'date' && !(/^\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}$/).test(val)) {//日期
                return false;
            } else if (norm == 'idCard' && val != '' && (!(/\d{17}[\d|x]|\d{15}/).test(val) || (val.length != 15 && val.length != 18))) {//身份证 //可稍后提供
                /*/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i*/
                return false;
            } else if (norm == 'posCode' && !(/\d{6}/).test(val)) {//邮政编码
                return false;
            } else if (norm == 'nhTourName' && (!((/^[A-Za-z]+$/).test(val) && (val.length >= 4 && val.length <= 26)) && !((/^[\u4e00-\u9fa5]+$/).test(val) && (val.length >= 2)))) {//南湖旅游人姓名规则

                //var a = "";
                //for (var i = 0; i < valarr.length; i++) {//删除行内空格
                //    var textArr = valarr[i].split('/');
                //    $.each(textArr, function (i, d) {
                //        a += d;
                //    });
                //}

                //if (!(/^[A-Za-z]+$/).test(a)) {
                //    return false;
                //} else {
                //    return true;
                //}

                return false;
            } else if (norm == 'address' && val.length < 2) {//地址, 2个以上汉子，包含英文数字跟标点符号
                return false;
            } else if (norm == 'password' && (val.length < 6 || val.length > 18)) {//南湖密码规则
                return false;
            }
            return true;
        },
        orderVerify: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox);
            //南湖PC官网订单页特别验证

            return true;
        }
    };
})(jQuery);

