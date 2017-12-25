
$(function () {
   
    //lazyload
    //$('img[data-original]').lazyload({
    //    threshold: 150,
    //    effect: 'fadeIn',
    //    skip_invisible: false,
    //    placeholder: '/Themes/v3/images/grey.gif'
    //});
    //窗口滚动
    $(window).on('scroll', function () {
        var $winWid = $(window).width(),
            leftWid = ($winWid - 1190) / 2,
            fixWid = $('.fix-left').width() + 10;
        if (leftWid <= fixWid) {
            $('.fix-left').css('left', '0');
        } else {
            $('.fix-left').css('left', leftWid - fixWid);
        }
        if ($winWid < 1190) {
            $('html').css('overflow-x', 'scroll');
            //$('body').css('overflow-x', 'scroll');
        } else {
            $('html').css('overflow-x', 'hidden');

            //$('body').css('overflow-x', 'hidden');
        }
    });
 



    //移动终端显示隐藏
    $('.top-bar .mob-put').mouseover(function () {
        $(this).find('.mbbox').show();
    });
    $('.mob-put .mbbox').mouseleave(function () {
        $(this).hide();
    });
    //top-bar城市切换
    $('.switch-city span').hover(function () {
        $(this).find('a').show();
    }, function () {
        $(this).find('a').hide();
    });
    //搜索产品选择下拉出现和隐藏
    $('#searchType').each(function () {
        var searchType = $(this).val() || "All"
            , keyWords = $("#searchKey").val() || ""
            , $SelectNode = $("div.switchbox .switch-ul li[data-type=" + searchType + "]")
            , $form = $("div.switchbox").closest('form')
            , searchTypeName = $SelectNode.text();
        $('.switchbox .switch-s').text(searchTypeName);
        $('.search-bar .search-txt').val(keyWords);
        $form.attr('action', '/search/' + searchType);
    });

    $('.switchbox .switch-s').on('click', function () {
        var $this = $(this),
            $form = $this.closest('form'),
            $switEm = $this.prev('em');
        if ($switEm.hasClass('row-up')) {
            $switEm.removeClass('row-up');
            $this.next('ul').hide();
        } else {
            $switEm.addClass('row-up');
            $this.next('ul').show();
            //！！有重复绑定点击事件。应该要调整
            $('.switch-ul li').on('click', function () {
                var txt = $(this).text(),
                    type = $(this).attr('data-type');
                $('.switchbox .switch-s').text(txt);
                $(this).parent().hide();
                $switEm.removeClass('row-up');
                $form.attr('action', '/search/' + $(this).attr('data-type'));
            });
            //!!
        }
    });
    $('.switchbox .switch-ul').mouseleave(function () {
        $(this).hide();
        $(this).siblings('em').removeClass('row-up');
    });

    //tabs
    $('.plate').tabs({
        btns: '.leb-cut>span', //所有按钮选择器
        targets: '.plate-list>ul', //所有目标选择器
        checkFirst: true,
        backFunc: function ($this, $backTarget, isNow) {
            if (isNow) return;
            var $imgs = $backTarget.find('img[data-original]');
            if ($imgs.length == 0) return;
            $(window).trigger('scroll');
        }
    });

    //抢优惠图片左右切换
    $('.favorable .favbox').hover(function () {
        $(this).find('.btn-box').show();
    }, function () {
        $(this).find('.btn-box').hide();
    });

    //nav的二级菜单鼠标移上去显示
    $('.nav-bar .laber').mouseenter(function () {

        if($(this).find('.sub-min .sub-nav li').length == 0) return false;

        var $this = $(this),
            $navBar = $this.closest('.nav-bar'),
            $target = $this.find('.subnav-wrap'),
            $navBox = $target.find('.sub-min'),
            $nav = $navBox.find('.sub-nav'),
            $choces = $this.find('.choces'),
            diffLeft = 198,
            navBarWidth = $navBar.width() - diffLeft,
            thisLeft = $this.position().left,
            centerPosition = thisLeft + ($this.width() / 2) - diffLeft,
            navLeft = 0;

        $choces.show();
        $target.show();

        if ($this.hasClass('ready')) return;

        $target.css({
            left: -thisLeft + diffLeft
        });

        navLeft = centerPosition - ($nav.width() / 2);

        if (centerPosition <= navBarWidth / 2) {
            //left
            if (navLeft < 0) navLeft = 0;
        } else {
            //right
            //if (navLeft + $nav.width() > $navBox.width()) navLeft = 0;
        }

        $nav.css({
            position:'absolute',
            left: navLeft
        });
        $this.addClass('ready');

    }).mouseleave(function () {
        var $this = $(this);
        $this.find('.choces').hide();
        $this.find('.subnav-wrap').hide();
    });

    //省内出境国内港澳显示更多目的地
    $('.chan-sbox .more').on('click', function () {
        var navigaCity = $(this).parents('.channel').siblings('.naviga-city');
        if (navigaCity.hasClass('block')) {
            navigaCity.hide();
            navigaCity.removeClass('block');
        } else {
            navigaCity.show();
            navigaCity.addClass('block');
        }

    });
    $('.bannerbox .naviga-city').mouseleave(function () {
        $(this).hide();
        $(this).removeClass('block');
    });

    //首页除外的目的地导航显示
    $('.navigation').on('click', function () {
        var $this = $(this),
        $em = $this.find('em');
        if ($em.hasClass('row-up') && !$this.hasClass('not')) {
            //已经打开
            $em.removeClass('row-up');
            $('.areabox').hide();

        } else {
            //已经关闭
            $em.addClass('row-up');
            $('.areabox').show();
            if (!$this.hasClass('not')) {
                $('.areabox').off('mouseleave').on('mouseleave', function () {
                    $this.trigger('click');
                });
            }
        }
    });
    //子地区导航
    $('.areabox').tabs({
        btns: '.naviga-box .area-laber', //所有按钮选择器
        targets: '.areaslist .naviga-city', //所有目标选择器
        model: 1,
        checkFirst: true
    });

    //关闭box
    $('.close:not(.not)').on('click', function () {
        $(this).parent().remove();
    });
    //关闭有效期弹窗
    $('.validity .close').on('click', function () {
        hideValidity();
    });

    //查看详情展示隐藏
    $('.plan-ul .inf-look').on('click', function () {
        var $this = $(this),
            $em = $this.find('em'),
            $thisparent = $this.parents('.plan-list'),
            $otherParent = $thisparent.siblings();
        if ($em.hasClass('row-up')) {
            $this.find('i').text('查看详情');
            $em.removeClass('row-up');
            $thisparent.find('.detail').css('display', 'none');
        } else {
            $this.find('i').text('收起详情');
            $em.addClass('row-up');
            $thisparent.find('.detail').show();
            $otherParent.find('.detail').hide();
            $otherParent.find('.inf-look i').text('查看详情');
            $otherParent.find('.inf-look em').removeClass('row-up');
        }
    });

    //右侧浮动的伸展
    var fixaWidth = $('.fix-right a').width(),
        addWidth = fixaWidth + 68,
        emWidth = $('.fix-right em').width(),
        addemWidth = emWidth + 68;

    $('.fix-right a').on({
        mouseenter: function () {
            var $this = $(this);
            $this.stop().animate({ width: "+" + addWidth }, 100);
            $this.next('em').stop().animate({ width: "+" + addemWidth }, 100);
        },
        mouseleave: function () {
            var $this = $(this);
            $this.stop().animate({ width: "+" + fixaWidth }, 100);
            $this.next('em').stop().animate({ width: "+" + emWidth }, 100);
        }
    });

    //banbar的文字banner-title的宽度
    var aWidth = $('.banbar .banner-title a').width(),
        aLength = $('.banbar .banner-title a').length,
        titleWidth = aWidth * aLength,
        marginWidth = aLength * 3,
        allWidth = titleWidth + marginWidth,
        floatLeft = allWidth / 2;
    $('.banbar .banner-title').css('margin-left', -floatLeft).css('width', allWidth);
    //banner的图片只有一张时隐藏左右箭头
    if ($('.banner li').length == 1) {
        $('.banner').find('.btn-left').hide();
        $('.banner').find('.btn-right').hide()
    }

    //对比
    if ($.nh.compare) $.nh.compare.binBtn();

    $('#minPrice').parents('.choceul').css('z-index', '2');
    if ($('#showMaskTips').length > 0) {
        showMaskTips();
        var $tipsVal = $('#showMaskTips').val();
        console.log($tipsVal);
        $('.tips span').text($tipsVal);
    }

    $('#topSearchForm').on('submit', function () {
        var $form = $(this),
            action = $form.attr('action'),
            key = $form.find('input[name="key"]').val();
        if (key == '') {
            alert('关键字不能为空!');
            return false;
        }
        if (key == '温泉') {
            $form.find('input[name="areaType"]').val(1);
            key += '&areaType=1';
        } else {
            $form.find('input[name="areaType"]').val('');
        }
        if (action.indexOf('?') != -1) action = action.split('?')[0];
        $form.attr('action', action + '?key=' + key);
    });
});

//退出登录
function firm() {
    //利用对话框返回的值 （true 或者 false） 
    if (confirm("您是否要退出登录？")) {
        location.href = "/member/json/logout";
    }
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
////IE版本的input提示语placeholder属性兼容
//$(function () {
//    if (!placeholderSupport()) {   // 判断浏览器是否支持 placeholder
//        $('[placeholder]').focus(function () {
//            var input = $(this);
//            if (input.val() == input.attr('placeholder')) {
//                input.val('');
//                input.removeClass('placeholder');
//            }
//        }).blur(function () {
//            var input = $(this);
//            if (input.val() == '' || input.val() == input.attr('placeholder')) {
//                input.addClass('placeholder');
//                input.val(input.attr('placeholder'));
//            }
//        }).blur();
//    };
//})
//function placeholderSupport() {
//    return 'placeholder' in document.createElement('input');
//}

function showLoading() {
    $.mask(0);
    $('.loading').stop().fadeIn();
}
function hideLoading() {
    $.mask(1);
    $('.loading').stop().fadeOut();
}

function showMaskTips() {
    $.mask(0);
    $('.tips').stop().fadeIn();
}
function hideMaskTips() {
    $.mask(1);
    $('.tips').stop().fadeOut();
}
//酒店门票有限期弹窗
function showValidity(obj) {
    $.mask(0);
    var Tilte = obj.data("vdata")
        , url = obj.data("vurl")
        , Target = $('.validity');
    Target.find("#validityContent").html(Tilte);
    Target.find("#linkurl").attr("href", url);
    Target.stop().fadeIn();
}
function hideValidity() {
    $.mask(1);
    $('.validity').stop().fadeOut();
}

//检测PC端/移动端打开
function testBrowser(address) {
    /*
    * 智能机浏览器版本信息:
    *
    */
    var hrefAddress;
    if (!address || address == '') {
        hrefAddress = "http://m.nanhutravel.com";
    }else{
        hrefAddress = address;
    }
   
    var browser = {
        versions: function () {
            var u = navigator.userAgent, app = navigator.appVersion;
            return {//移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1,//IE内核
                presto: u.indexOf('Presto') > -1,//opera内核
                webKit: u.indexOf('AppleWebKit') > -1,//苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),//是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),//ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
                iPhone: u.indexOf('iPhone;') > -1, //是否为iPhone或者QQHD浏览器  || u.indexOf('Mac') > -1
                iPad: u.indexOf('iPad;') > -1,//是否iPad
                webApp: u.indexOf('Safari') == -1//是否web应该程序，没有头部与底部
            };
        }(),
        language: (navigator.browserLanguage || navigator.language).toLowerCase()
    }
    if (browser.versions.android || browser.versions.iPhone || browser.versions.iPad) {
        //去掉浏览器的判断
        //if (ISeachEngines()) {
        window.location.href = hrefAddress;
        //}
    }
    function ISeachEngines() {
        var r = document.referrer;
        r = r.toLowerCase(); //转为小写
        var aSites = new Array('google.', 'baidu.', 'soso.', 'so.', '360.', 'yahoo.', 'youdao.', 'sogou.', 'gougou.');
        var b = false;
        for (i in aSites) {
            if (r.indexOf(aSites[i]) > 0) {
                b = true;
                break;
            }
        }
        return b;
    }
}