/*
 *
 * jQuery.nhSlider for pc v1.0
 * http://www.nanhutralel.com
 *
 * Author: Eeqlee
 * Copyright (c) 2015 @nanhutralel.com
 *
 */
(function ($) {
    $.fn.nhSlider = function (userOptions) {
        return this.each(function () {
            var settings = {
                targetBox: $(this)
            };
            var options = $.extend(settings, userOptions);
            $.nhSlider.set(options);
        });
    };
    $.nhSlider = {
        settings: $.extend({
            targetBox: '.banner',
            effect: 'left', //fade,left,right,top,bottom
            transition: 700, //Unit: ms
            interval: 3000, //Unit: ms
            navBtn: true,
            numBtn: false,
            dotBtn: true,
            arrowBtn: false,
            targetBoxZ: 9999,
            boxBgColor: '#333',
            showTimer: '',
            touchAble: 'ontouchstart' in window,
            swipe: true
        }, {}),
        set: function (options) {
            this.settings = $.extend(this.settings, options);
            this.start();
        },
        initialize: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $itemBox = $targetBox.find('ul'),
                $items = $itemBox.find('li'),
                $item = $items.first(),
                itemsCloneBefore = $items.clone(),
                itemsCloneAfter = $items.clone();

            $items.each(function (i) {
                $(this).attr('data-s', i+1);
            });
            thisFun.createNavBtn($items);

            itemsCloneBefore.addClass('not');
            itemsCloneAfter.addClass('not');
            $itemBox.prepend(itemsCloneBefore).append(itemsCloneAfter);

            $('body').css({
                'overflow-x': 'hidden'
            });
            $targetBox.css({
                position: 'relative',
                height: $item.outerHeight()
            });

            $itemBox.css({
                width: $item.width() * ($itemBox.find('li')).length,
                position: 'absolute',
                top: 0,
                left: 0,
                margin: 0
            });

            $targetBox.addClass('initialized');
            thisFun.start();
        },
        start: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox);
            if ($targetBox.length != 0) {
                if (!$targetBox.hasClass('initialized')) {
                    thisFun.initialize();
                } else {
                    thisFun.run();
                    thisFun.events();
                }
            }
        },
        events: function () {//事件监听绑定
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $itemBox = $targetBox.find('ul'),
                $items = $itemBox.find('li'),
                $navBtnBox = $targetBox.find('.banner-title'),
                $navBtns = $navBtnBox.find('a');

            //鼠标经过
            $targetBox.mouseenter(function (event) {
                event.preventDefault();
                thisFun.stop();
            }).mouseleave(function (event) {
                event.preventDefault();
                thisFun.run();
            });

            //导航按钮
            if (thisFun.settings.navBtn) {
                $navBtns.on('click', function () {
                    var index = parseInt($(this).attr('data-num'));
                    $(this).addClass('on').siblings().removeClass('on');
                    thisFun.show(index);
                }).first().trigger('click');
            }

            //function toPosi(num) {
            //    if (num && num < 0) {
            //        num = -num;
            //    }
            //    return num;
            //}
        },
        createNavBtn: function ($items) {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $itemBox = $targetBox.find('ul'),
                $item = $items.first(),
                navBtnBox = '<div class="banner-title"></div>',
                $navBtnBox,
                navBtnHtml = '';

            $targetBox.append(navBtnBox);
            $navBtnBox = $targetBox.find('.banner-title');
            $items.each(function () {
                var $this = $(this);
                thisNum = $this.index() + 1;
                navBtnHtml += '<a data-num="' + thisNum + '">' + $this.attr('title') + '</a>';//' + thisNum + '
            });
            $navBtnBox.html(navBtnHtml);

        },
        show: function (index) {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $itemBox = $targetBox.find('ul'),
                $target = $itemBox.find('li[data-s="' + index + '"]'),
                targetPos = $target.position().left;

            $itemBox.stop().animate({
                left: -targetPos
            }, 300);
        },
        run: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $btns = $targetBox.find('.banner-title a'),
                len = $btns.length;
            thisFun.showTimer = setInterval(function () {
                var index = parseInt($btns.filter('.on').attr('data-num'));
                if (index == len) {
                    index = 1;
                } else {
                    index++;
                }
                $btns.eq(index-1).trigger('click');
            }, thisFun.settings.interval + thisFun.settings.transition);
        },
        stop: function () {
            clearInterval(this.showTimer);
        }
    };
})(jQuery);

function bannerMask(){
    var $win = $(window),
        winWidth = $win.width(),
        bannerWidth = $('.bannerbox').width(),
        $shades = $('.shadebox>div');
    if (winWidth <= bannerWidth){
        $shades.hide();
    } else {
        //console.log(winWidth - bannerWidth, winWidth , bannerWidth);
        $shades.width((winWidth - bannerWidth) / 2);
    }
}

/*
 *
 * jQuery.eeqSimpleTabs v1.1
 *
 * Copyright (c) 2013 @Eeqlee
 * Licensed under the MIT.
 *
 */
function tabs($t, btns, targets, nowClass, model) {
    $t.each(function () {
        var $tabBox = $(this),
            $btns = $tabBox.find(btns),
            $targets = $tabBox.find(targets),
            event = model == 1 ? 'mouseenter' : 'click';
        $btns.on(event, function () {
            var $this = $(this),
                index = $this.index();
            $this.addClass(nowClass).siblings().removeClass(nowClass);
            $targets.eq(index).addClass(nowClass).siblings().removeClass(nowClass);
        });
        if (model == 1) {
            $tabBox.on('mouseleave', function () {
                $targets.removeClass(nowClass);
            });
        }
        if (model != 1) $btns.first().trigger('click');
    });
}

//异步获取2.0后台推广内容
function getRecommend(options) {
    var defaultSettings = {
        url: 'http://api.nanhutravel.com/SiteAccess/MicroMall/AdInfo.ashx',
        action: 'GetADAndPriceByCode',
        code: false,
        backFunc: function () { }
    },
        ops = $.extend(defaultSettings, options);

    if (!ops.code) return;

    $.ajax({
        url: ops.url,
        type: "GET",
        dataType: 'jsonp',
        jsonp: 'jsonp',
        data: {
            action: ops.action,
            code: ops.code
        },
        success: function (reObj) {
            //console.log(reObj);
            if (reObj.ret == 0) {
                var itemsArray = reObj.data.items;
                itemsArray = $.grep(itemsArray, function (n, i) {
                    //过滤掉不显示的图片
                    return n.isFix == true;
                });
                ops.backFunc(itemsArray, reObj.data.name);
            }
        }
    });
}

//获取BANNER
function getBanners() {
    var count = $('.bannerbox .banner-list li').length,
        okCount = 0;
    $('.bannerbox .banner-list li').each(function (i, d) {
        var $box = $(this);
        getRecommend({
            code: 'newbanner_' + (i + 1),
            backFunc: function (ret, name) {
                $box.attr('title', name).html(bannerHtml(ret, name));
                okCount++;
            }
        });
    });

    var timer = setInterval(function () {
        if (okCount == count) {
            clearInterval(timer);
            bannerMask();
            $('.bannerbox').nhSlider();
        }
    }, 300);

    function bannerHtml(ret, name) {
        var html = '';
        $.each(ret, function (i, d) {
            if (i > 3) return;
            var bigClass = 'banner-big';
            if (i != 0) bigClass = '';
            html += '<a class="' + bigClass + '" href="' + d.link + '" title="' + d.title + '"><img src="http://m.nanhutravel.com/' + d.imageUrl + '" /></a>';
        });
        return html;
    }
}

/*
 *
 * jQuery.scrollToElm v1.0
 *
 * Copyright (c) 2013 @Eeqlee
 * Licensed under the MIT.
 *
 */
function scrollToElm(targetObject, targetName, nowItemClassName, scrollDis) {
    var $targetObject = targetObject;
    $targetObject.click(function () {
        var $item = $(this),
            targetMark = $item.attr(targetName),
            $target = $('#' + targetMark).length == 0 ? $('.' + targetMark) : $('#' + targetMark);
        $('html,body').animate({ scrollTop: $target.offset().top - 65 }, scrollDis);
        if ($item.is('li')) {
            $item.addClass(nowItemClassName).siblings().removeClass(nowItemClassName);
        }
    });
}

//获取身份证详细信息
function getCardDetails(cardNo) {
    if (!(/\d{17}[\d|x]|\d{15}/).test(cardNo)) return false;
    var myDate = new Date(),
        month = myDate.getMonth() + 1,
        day = myDate.getDate(),
        age = myDate.getFullYear() - cardNo.substring(6, 10) - 1,
        gender,
        birth;

    //获取出生日期
    birth = cardNo.substring(6, 10) + "-" + cardNo.substring(10, 12) + "-" + cardNo.substring(12, 14);


    //获取性别
    if (parseInt(cardNo.substr(16, 1)) % 2 == 1) {
        //男
        gender = 'M';
    } else {
        //女
        gender = 'F';
    }

    //获取年龄
    if (cardNo.substring(10, 12) < month || cardNo.substring(10, 12) == month && cardNo.substring(12, 14) <= day) {
        age++;
    }
    return {
        age: age,
        gender: gender,
        birth: birth
    };

}

//计算生日
function getAge(birthday, nowDate) {
    var nowDate = newDate(nowDate),
        month = nowDate.getMonth() + 1,
        day = nowDate.getDate(),
        birthday = newDate(birthday),
        birthMonth = birthday.getMonth() + 1,
        birthDate = birthday.getDate(),
        age = nowDate.getFullYear() - birthday.getFullYear() - 1;
    if (birthMonth < month || birthMonth == month && birthDate <= day) {
        age++;
    }
    return age;
}

//日期补0(兼容IE)
function dateUse(date) {
    var d = date.split('-');
    d[1] = d[1].length == 1 ? 0 + d[1] : d[1];
    d[2] = d[2].length == 1 ? 0 + d[2] : d[2];
    return d[0] + '-' + d[1] + '-' + d[2];
}

//加减日期 model int 0:加 1:减
function operaDate(date, days, model) {
    var d = newDate(date),
        m;
    if (model == 1) {
        d.setDate(d.getDate() - days);
    } else {
        d.setDate(d.getDate() + days);
    }
    m = d.getMonth() + 1;
    //console.log(d.getFullYear() + '-' + m + '-' + d.getDate());
    return d.getFullYear() + '-' + m + '-' + d.getDate();
}

//星期日数字转中文字符
function intWeek2chsWeek(int) {
    var string = '',
        arr = ['日', '一', '二', '三', '四', '五', '六'];
    int = parseInt(int);
    string = arr[int];
    return string;
}

function getWeekDay(date) {
    date = newDate(date);
    //return (date.getDay() == 0) ? 6 : date.getDay()
    return date.getDay();
}

//兼容旧版IE的字符串转DATE
function newDate(date) {
    date = dateUse(date);
    date = !$.support.leadingWhitespace ? parseISO8601(date) : new Date(date);
    return date;
}
//字符串转IE
function parseISO8601(dateString) {
    var timedate = new Date(),
        d = dateString.split('-');
    timedate.setFullYear(d[0]);
    timedate.setMonth(d[1] - 1);
    timedate.setDate(d[2]);
    return timedate;
}

//获取URL参数
function getQuery(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}

//JSON转换为字符串
function JSONstringify(json) {
    var result;
    //if(!$.support.leadingWhitespace){
    //    result = (new Function("return " + json))();
    //}else{
        result = JSON.stringify(json);
    //}
    return result;
}