/*
 *
 * jQuery.nh.extend for pc v1.0
 * http://www.nanhutralel.com
 *
 * Author: Eeqlee
 * Copyright (c) 2016 @nanhutralel.com
 *
 */

//define(['jquery'], function ($) {

//    return function () {
$.nh = { search: {}, order: {} };
$.nh.maskOpened = false;

(function () {
    //$.ajaxSetup({
    //    timeout: 10000,
    //    error: function (jqXHR, textStatus, errorThrown) {
    //        switch (jqXHR.status) {
    //            case (500):
    //                alert('网络错误,请稍候再试.');
    //                break;
    //            case (400):
    //                alert('网络错误,请稍候再试.');
    //                break;
    //            case (302):
    //                alert('网络错误,请稍候再试.');
    //                break;
    //            case (401):
    //                alert('请先登录');
    //                break;
    //            case (403):
    //                alert('无权限执行此操作');
    //                break;
    //            case (408):
    //                alert('请求超时');
    //                break;
    //            default:
    //                alert('未知错误');
    //        }
    //        $.mask(1);
    //    }
    //});
})();

jQuery.extend({
    //get: set: cookies
    cookie: function (key, value, options) {
        if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
            options = $.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires,
                    t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        options = value || {};
        var decode = options.raw ? function (s) {
            return s;
        } : decodeURIComponent;

        var pairs = document.cookie.split('; ');
        for (var i = 0, pair; pair = pairs[i] && pairs[i].split('=') ; i++) {
            if (decode(pair[0]) === key) return decode(pair[1] || '');
        }
        return null;
    },
    //遮罩, 0:打开, 1:关闭
    mask: function (state, callback) {
        var $mask = $('body>.mask'),
            //$load = $('body>.loading'),
            speed = 300;
        switch (state) {
            case 0:
                if ($.nh.maskOpened) return;
                if (!$mask || $mask.length == 0) $('body').append('<div class="mask" style="display:none;"></div>');
                //if (!$load || $load.length == 0) $('body').append('<div class="loading" style="display:none;"><i class="icon"></i></div>');
                $.nh.maskOpened = true;
                $('html,body').css('overflow-y', 'hidden');
                $mask = $('body>.mask');
                $mask.fadeIn(speed);
                //$load = $('body>.loading');
                //$mask.fadeIn(speed, function () {
                //    $load.fadeIn(speed);
                //});
                break;
            default:
                if (!$.nh.maskOpened) return;
                if (!$mask || $mask.length == 0) return;
                $.nh.maskOpened = false;
                $('html,body').css('overflow-y', 'auto');
                $mask.fadeOut(speed);
                //$load.fadeOut(speed, function () {
                //    $mask.fadeOut(speed);
                //});
        };
        if (callback) callback();
    },
    parseParam: function (param, key) {
        var paramStr = "";
        if (param instanceof String || param instanceof Number || param instanceof Boolean) {
            paramStr += "&" + key + "=" + encodeURIComponent(param);
        } else {
            $.each(param, function (i) {
                var k = key == null ? i : key + (param instanceof Array ? "[" + i + "]" : "." + i);
                paramStr += '&' + $.parseParam(this, k);
            });
        }
        return paramStr.substr(1);
    },
    calDate: function (startDate, endDate) {
        var strSeparator = '-'; //日期分隔符
        var oDate1;
        var oDate2;
        var days;
        oDate1 = startDate.split(strSeparator);
        oDate2 = endDate.split(strSeparator);
        var strDateS = new Date(oDate1[0], oDate1[1] - 1, oDate1[2]);
        var strDateE = new Date(oDate2[0], oDate2[1] - 1, oDate2[2]);
        days = parseInt(Math.abs(strDateS - strDateE) / 1000 / 60 / 60 / 24) //把相差的毫秒数转换为天数
        return days;
    },


    /*
     * jQuery.scrollToElm v1.0
     * Copyright (c) 2013 @Eeqlee
     * Licensed under the MIT.
     */
    scrollToElm: function (targetObject, targetName, nowItemClassName, scrollDis) {
        var $targetObject = targetObject;
        $targetObject.click(function () {
            var $item = $(this),
                targetMark = $item.attr(targetName),
                $target = $('#' + targetMark).length == 0 ? $('.' + targetMark) : $('#' + targetMark);
            $('html,body').animate({
                scrollTop: $target.offset().top - 50
            }, scrollDis);
            if ($item.is('li')) {
                $item.addClass(nowItemClassName).siblings().removeClass(nowItemClassName);
            }
        });
    },
    //获取URL参数
    getQuery: function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    },
    //替换字符串
    replaceAll: function (str, sptr, sptr1) {
        while (str.indexOf(sptr) >= 0) {
            str = str.replace(sptr, sptr1);
        }
        return str;
    },
    //兼容旧版IE的字符串转DATE
    newDate: function (date) {
        if (!date || date == '') return date;
        if ($.type(date) == 'date') {
            return date;
        }

        date = this.dateUse(date);
        //date = !$.support.leadingWhitespace ? parseISO8601(date) : new Date(date);
        //if (!$.support.leadingWhitespace) {
        var newDate = new Date();
        if (date.indexOf(' ') == -1 && date.indexOf(':')==-1) {
            var dateArr = date.split('-');
            newDate.setFullYear(dateArr[0], dateArr[1] - 1, dateArr[2]);
            newDate.setHours(0, 0, 0, 0);
            date = newDate;
        } else {
            var arr = date.split(' '),
                dateArr = arr[0].split('-'),
                timeArr = arr[1].split(':');
            newDate.setFullYear(dateArr[0], dateArr[1] - 1, dateArr[2]);
            newDate.setHours(timeArr[0], timeArr[1], timeArr[2]);
            date = newDate;
        }
        //} else {
        //    date = new Date(date);
        //}

        return date;
    },
    //日期补0(兼容IE)
    dateUse: function (date) {
        var d = date.split('-');
        d[1] = d[1].length == 1 ? 0 + d[1] : d[1];
        d[2] = d[2].length == 1 ? 0 + d[2] : d[2];
        return d[0] + '-' + d[1] + '-' + d[2];
    },
    //加减日期 model int 0:加 1:减
    operaDate: function (date, days, model) {
        var d = $.type(date) == 'date' ? date : this.newDate(date),
            m;
        if (model == 1) {
            d.setDate(d.getDate() - days);
        } else {
            d.setDate(d.getDate() + days);
        }
        m = d.getMonth() + 1;
        //console.log(d.getFullYear() + '-' + m + '-' + d.getDate());
        return d.getFullYear() + '-' + m + '-' + d.getDate();
    },
    //通过生日计算年龄
    getAge: function (birthday, nowDate) {
        var nowDate = this.newDate(nowDate),
            month = nowDate.getMonth() + 1,
            day = nowDate.getDate(),
            birthday = this.newDate(birthday),
            birthMonth = birthday.getMonth() + 1,
            birthDate = birthday.getDate(),
            age = nowDate.getFullYear() - birthday.getFullYear() - 1;
        if (birthMonth < month || birthMonth == month && birthDate <= day) {
            age++;
        }
        return age;
    },
    //身份证规则
    deepCheckIDCard: function (sCardNo) {
        var aCity = { 11: "北京", 12: "天津", 13: "河北", 14: "山西", 15: "内蒙古", 21: "辽宁", 22: "吉林", 23: "黑龙江", 31: "上海", 32: "江苏", 33: "浙江", 34: "安徽", 35: "福建", 36: "江西", 37: "山东", 41: "河南", 42: "湖北", 43: "湖南", 44: "广东", 45: "广西", 46: "海南", 50: "重庆", 51: "四川", 52: "贵州", 53: "云南", 54: "西藏", 61: "陕西", 62: "甘肃", 63: "青海", 64: "宁夏", 65: "新疆", 71: "台湾", 81: "香港", 82: "澳门", 91: "国外" };
        var flag = false;
        var val1 = sCardNo;
        var len = val1.length;
        var BirYear, BirMonth, BirDay, iAge;
        if (len == 0) {
            return;
        };

        if (len == 15) {
            if (!/^\d{15}$/i.test(val1)) {
                flag = false;
            } else if (aCity[parseInt(val1.substr(0, 2))] == null) {
                flag = false;
            } else {
                flag = true;
            };
        } else if (len == 18) {
            if (!/^\d{17}(\d|x)$/i.test(val1)) {
                flag = false;
            } else if (aCity[parseInt(val1.substr(0, 2))] == null) {
                flag = false;
            } else {
                birthday = val1.substr(6, 4) + "-" + Number(val1.substr(10, 2)) + "-" + Number(val1.substr(12, 2));
                var d = new Date(birthday.replace(/-/g, "/"));
                if (birthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate())) {
                    flag = false;
                } else {
                    flag = true; //VerifyIDCard(val1);
                };
                BirYear = d.getFullYear();
                BirMonth = d.getMonth();
                BirDay = d.getDate();
            };
        } else {
            flag = false;
        };
        return flag;

    }
});

//
jQuery.fn.extend({
    /*
     *
     * jQuery.eeqSimpleTabs v1.3
     *
     * Copyright (c) 2013-2016 @Eeqlee
     * Licensed under the MIT.
     *
     */
    tabs: function (options) {
        var defaultSettings = {
            $t: this, //总容器
            btns: '.btn-item', //所有按钮选择器
            targets: '.target-item', //所有目标选择器
            targetName: false, //按钮内目标属性
            silbling: false, //目标兄弟元素选择器
            nowClass: 'now', //当前(显示)class
            model: 0, //触发TAB模式, 0:点击, 1:鼠标经过
            clickClose: false, //再次点击TAB按钮时, 是否关闭TAB
            checkFirst: false, //是否默认选中第一个TAB
            effect: false,
            backFunc: function () { } //点击回调
        },
            settings = $.extend(defaultSettings, options),
            nowClass = settings.nowClass,
            model = settings.model;

        $(settings.$t).each(function () {
            var $tabBox = $(this),
                $btns = $tabBox.find(settings.btns).not('.not'),
                $targets = $tabBox.find(settings.targets),
                event = model == 1 ? 'mouseenter' : 'click';
            $btns.on(event, function () {
                var $this = $(this),
                    index = $this.index(),
                    isNow = $this.hasClass(nowClass);
                if (!isNow) {
                    $this.addClass(nowClass).siblings().removeClass(nowClass);
                } else if (model != 1 && settings.clickClose) {
                    $this.removeClass(nowClass);
                }
                showHideTarget($this, $targets, index, isNow);
            });
            if (model == 1) {
                $tabBox.on('mouseleave', function () {
                    $targets.removeClass(nowClass);
                });
            }
            if (model != 1 && settings.checkFirst) $btns.first().trigger('click');

            function showHideTarget($this, $targets, index, isNow) {
                var $backTarget;
                if (!settings.targetName || !settings.silbling) {
                    $backTarget = $targets.eq(index);

                    if (isNow && model != 1 && settings.clickClose) {
                        $backTarget.removeClass(nowClass);
                    } else {
                        $backTarget.addClass(nowClass).siblings().removeClass(nowClass);
                    }
                } else {
                    var targetMark = $this.attr(settings.targetName),
                        $target = $('#' + targetMark).length == 0 ? $tabBox.find('.' + targetMark) : $('#' + targetMark);
                    $backTarget = $target;

                    if (isNow && model != 1 && settings.clickClose) {
                        $backTarget.removeClass(nowClass);
                    } else {
                        $backTarget.addClass(nowClass);
                        $tabBox.find(settings.silbling).not($backTarget).removeClass(nowClass);
                    }

                }
                //回调
                settings.backFunc($this, $backTarget, isNow);
            }
        });
    },
    //弹窗
    nhDialog: {
        open: function (options) {
            this.core(options);
        },
        close: function () {
            $(this).hide();
            $.mask(1);
        },
        core: function (options) {
            var defaultSettings = {
                $t: this, //总容器
                mask: true,
                backFunc: function () { } //点击回调
            },
                settings = $.extend(defaultSettings, options),
                model = settings.model;

            if (this.mask) $.mask(0);

        }
    },
    //加减按钮事件
    numSelecter: function (options) {
        var defaultSettings = {
            target: this, //总容器
            inputClass: '.count-ipt',
            btnDownClass: '.count-down',
            btnUpClass: '.count-up',
            baseValue: 0,
            step: 1, //步长
            backFunc: function () { } //点击回调
        },
            settings = $.extend(defaultSettings, options),
            $target = settings.target;

        $target.each(function () {
            var $box = $(this),
                $input = $box.find(settings.inputClass),
                $btnDown = $box.find(settings.btnDownClass),
                $btnUp = $box.find(settings.btnUpClass),
                baseValue = settings.baseValue,
                step = settings.step,
                maxValue = parseInt($input.attr('max')) || false,
                minValue = parseInt($input.attr('min')) || false;
            $input.prop('readonly', true);
            //减
            $btnDown.off('click').on('click', function () {
                baseValue = parseInt($input.val());
                /*if (minValue && baseValue <= minValue) {
                    setValue(minValue);
                    return;
                } */
                if (baseValue <= 0) {
                    setValue(0);
                    return;
                }
                setValue(baseValue - step);
            });
            //加
            $btnUp.off('click').on('click', function () {
                baseValue = parseInt($input.val());
                if (maxValue && baseValue >= maxValue) {
                    setValue(maxValue);
                    return;
                }
                setValue(baseValue + step);

            });

            function setValue(value) {
                $input.attr({
                    'data-val': value,
                    'data-count': value,
                    'value':value   
                }).val(value).trigger('change');
             
                    var tour = $('#adultSum');
                    var countPayment = $('#adultSum').attr('data-val') * $('#adultSum').attr('data-price');
                   $('#numBer').html($('#adultSum').attr('data-val')+' X ¥');
                      $('#sum-pay').html(countPayment);
                     $('#sum-count').html(countPayment);
                   //$('.actual-payment').text(countPayment);
            }
        });
    },
    //图片延时加载
    lazyLoad: function (options) {
        var defaultSettings = {
            container: window,
            effect: 'fadeIn',
            data_attribute: 'data-original',
            placeholder: '/Themes/v3/images/fill-w.png'
        },
            settings = $.extend(defaultSettings, options),
            $container = $(settings.container),
            containerHeight = $container.height(),
            $imgs = $(this);
        $imgs.attr('src', settings.placeholder);
        $container.on('scroll', function () {
            var scrollTop = $container.scrollTop() + containerHeight;
            $imgs.filter(':visible').filter(':not(.ready)').each(function () {
                var $img = $(this),
                    imgOffsetTop = $img.offset().top;
                if (scrollTop >= imgOffsetTop && !$img.hasClass('ready')) {
                    $img.attr('src', $img.attr(settings.data_attribute)).addClass('ready').removeAttr(settings.data_attribute);
                    $img.css({ display: 'none' })[settings.effect](500);
                }
            });
        });
        $container.trigger('scroll');
    },
    //左滑隐藏
    slideLeftHide: function (speed, callback) {
        this.animate({
            width: "hide",
            paddingLeft: "hide",
            paddingRight: "hide",
            marginLeft: "hide",
            marginRight: "hide"
        }, speed, callback);
    },
    //左滑显示
    slideLeftShow: function (speed, callback) {
        this.animate({
            width: "show",
            paddingLeft: "show",
            paddingRight: "show",
            marginLeft: "show",
            marginRight: "show"
        }, speed, callback);
    },
    //form to json
    serializeJson: function () {
        var serializeObj = {};
        var array = this.serializeArray();
        var str = this.serialize();
        $(array).each(function () {
            if (serializeObj[this.name]) {
                if ($.isArray(serializeObj[this.name])) {
                    serializeObj[this.name].push(this.value);
                } else {
                    serializeObj[this.name] = [serializeObj[this.name], this.value];
                }
            } else {
                serializeObj[this.name] = this.value;
            }
        });
        return serializeObj;
    }
    
});

jQuery.extend(jQuery.easing, {
    easeOutBounce: function (x, t, b, c, d) {
        if ((t /= d) < (1 / 2.75)) {
            return c * (7.5625 * t * t) + b;
        } else if (t < (2 / 2.75)) {
            return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
        } else if (t < (2.5 / 2.75)) {
            return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
        } else {
            return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
        }
    }
});
//    }
//});

