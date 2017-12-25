/*
 *
 * jQuery.nhCalendar for PC v1.0
 * http://www.nanhutravel.com
 *
 * Author Eeqlee
 * Create 2015-11-27
 * Last edit 2016-04-07
 *
 * Copyright (c) 2015-2016 www.nanhutravel.com.
 *
 */
(function ($) {
    $.fn.nhCalendar = function (userOptions) {
        return this.each(function () {
            var settings = {
                targetBox: $(this)
            };
            var options = $.extend(settings, userOptions);
            $.nhCalendar.set(options);
        });
    };
    $.nhCalendar = {
        settings: $.extend({
            targetBox: '.calendar',
            details: false,
            year: 2015,
            month: 1,
            date: { year: '', month: '', day: '' },
            dayOnClickBackFunc: function () { },
            setCalendarBackFunc: function () { },
            succBackFunc: function () { }
        }, {}),
        set: function (options) {
            this.settings = $.extend(this.settings, options);
            this.start();
        },
        initialize: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox);
            //if ($targetBox.find('.calendarbox').length == 0)
            thisFun.createCalendar();
            $targetBox.addClass('initialized');
            thisFun.start();
        },
        start: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox);
            if ($targetBox.length != 0) {
                $targetBox.addClass('creating');
                if (!$targetBox.hasClass('initialized')) {
                    thisFun.initialize();
                } else {
                    thisFun.setCalendar(thisFun.settings.year, thisFun.settings.month);
                    thisFun.events();
                    //
                    $targetBox.removeClass('creating');
                }
            }
        },
        events: function () {//事件监听绑定
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                $arrowLeft = $targetBox.find('.change-date .left-arrow'),
                $arrowRight = $targetBox.find('.change-date .right-arrow'),
                year,
                month;

            //箭头点击切换同月分事件
            //$arrowLeft.on('click', function () {
            //    year = thisFun.settings.date.year;
            //    month = thisFun.settings.date.month;
            //    if (month == 1) {
            //        year--;
            //        month = 12;
            //    } else {
            //        month--;
            //    }
            //    //if (year == thisFun.settings.year && month == thisFun.settings.month) return;
            //    thisFun.setCalendar(year, month);
            //});
            //$arrowRight.on('click', function () {
            //    year = thisFun.settings.date.year;
            //    month = thisFun.settings.date.month;
            //    if (month == 12) {
            //        year++;
            //        month = 1;
            //    } else {
            //        month++;
            //    }
            //    thisFun.setCalendar(year, month);
            //});

            $targetBox.find('li').on('click', function () {
                var $item = $(this);
                //$box = $item.nextAll('.detailbox').first();
                //if ($box.length == 0 || !$box) return;

                thisFun.settings.dayOnClickBackFunc($item);
            });
            thisFun.settings.succBackFunc($(thisFun.settings.targetBox));
        },
        createCalendar: function () {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                cHtml = '';

            // cHtml += '<li class="week-end">星期日</li><li class="week">星期一</li><li class="week">星期二</li><li class="week">星期三</li><li class="week">星期四</li><li class="week">星期五</li><li class="week-end">星期六</li><li class="w-end">日</li><li class="w">一</li><li class="w">二</li><li class="w">三</li><li class="w">四</li><li class="w">五</li><li class="w-end">六</li>';
            for (var i = 0; i < 6; i++) {
                cHtml += '';
                for (var j = 1; j <= 7; j++)

                    cHtml += '<li id=day' + (i * 7 + j) + '><h2>0</h2></li>';
                // if (thisFun.settings.details) cHtml += '<div class="detailbox" style="display:none;"></div>';
            }
            $targetBox.html(cHtml);

        },
        setCalendar: function (y, m) {
            var thisFun = this,
                $targetBox = $(thisFun.settings.targetBox),
                date = new Date(y, (m - 1), 1),
                mv = date.getDay(),
                d = date.getDate(),
                yy = date.getFullYear(),
                mm = date.getMonth(),
                $day;

            //备用
            thisFun.settings.date.year = y;
            thisFun.settings.date.month = m;

            //$("#month").html(mm > 9 ? mm : '0' + mm);
            // $("#month").html(mm+1);
            for (var i = 1; i <= mv; i++) {
                $day = $("#day" + i);
                $day.attr({
                    year: '',
                    month: '',
                    day: ''
                }).html('');
            }
            while (date.getMonth() == mm) {
                $day = $("#day" + (d + mv));
                $day.attr({
                    dateval: date.valueOf(),
                    date: yy + '-' + ((mm + 1) < 10 ? '0' + (mm + 1) : (mm + 1)) + '-' + (d < 10 ? '0' + d : d),
                    year: yy,
                    month: mm + 1,
                    day: d
                }).addClass('date').html('<em>' + d + '</em>');
                date.setDate(++d);
            }
            while (d + mv <= 42) {
                $day = $("#day" + (d + mv));
                $day.attr({
                    year: '',
                    month: '',
                    day: ''
                }).html('');
                d++;
            }

            var $lastLine = $('#day36,#day37,#day38,#day39,#day40,#day41,#day42'),
                lastLineHide = true;
            $lastLine.each(function () {
                if ($(this).find('em').length > 0) lastLineHide = false;
            });
            if (lastLineHide) {
                $lastLine.hide();
            } else {
                $lastLine.show();
            }
            $targetBox.attr({ 'data-year': y, 'data-month': m });

            thisFun.settings.setCalendarBackFunc(y, m, $(thisFun.settings.targetBox));

        }
    };
})(jQuery);

function hotelInOut($this, inputs, backFunc) {
    var $box = $this.closest('.calendarbox'),
        $parent = $this.closest('.day'),
        $items = $box.find('.date'),
        $present = $items.filter('.inout'),
        presentDay = $present.length == 1 ? parseInt($present.attr('dateval')) : false,
        thisDay = parseInt($this.attr('dateval')),
        days = false,
        $sdate = false,
        sdate = '',
        $edate = false,
        edate = '';

    if (inputs) {
        $sdate = $(inputs[0]);
        $edate = $(inputs[1]);

        //日历翻页后读取入住日期Input控件里是否有值
        if (!presentDay && $sdate.val() != "") {
            presentDay = parseInt($sdate.attr("dateval"));
        }
    }

    if (!$this.hasClass('item')) {
        if (presentDay && presentDay < thisDay && $edate.val() == "") {

            //特殊离店
            $this.addClass('inout out').find('p').prepend('<span class="txt">离店</span>');

            if (!checkCen()) {
                alert('所选日期段中存在没有计划的日期, 请重新选择');
                days = 0;
                $this.removeClass('inout out').find('span:last').remove();
            } else {
                //中间样式
                days = setCen() + 1;
            }
            //
            if (backFunc) backFunc($this, 'out', days);

        } else {
            if (backFunc) backFunc($this, false);
        }
        return;
    }
    if (!$present || ($present.length == 0 && $sdate.val() == "")) {
        //入住
        $this.addClass('inout in').find('p').prepend('<span class="txt">入住</span>');

        //
        if (backFunc) backFunc($this, 'in');
    } else if (presentDay && presentDay < thisDay && $edate.val() == "") {
        //离店
        $this.addClass('inout out').find('p').prepend('<span class="txt">离店</span>');

        if (!checkCen()) {
            alert('所选日期段中存在没有计划的日期, 请重新选择');
            days = 0;
            $this.removeClass('inout out').find('span:last').remove();
        } else {
            //中间样式
            days = setCen() + 1;
        }
        //
        if (backFunc) backFunc($this, 'out', days);
        //} else if ($this.hasClass('inout') || !presentDay || (presentDay && presentDay > thisDay)) {
        //    //其他日期
        //    var pArr = [];
        //    $present.each(function () {
        //        pArr.push(parseInt($(this).attr('dateval')));
        //    });
        //    if (pArr[0] < thisDay && thisDay < pArr[1]) return;
        //    $items.each(function () {
        //        var $item = $(this);
        //        $item.removeClass('cen');
        //        if ($item.hasClass('inout')) $item.removeClass('inout in out').find('span:last').remove();
        //    });
        //    if ($this.hasClass('pseudo')) $this.removeClass('item pseudo');
        //    if (backFunc) backFunc($this, 'cancel');
    } else {
        //其他日期
        var pArr = [];
        $present.each(function () {
            pArr.push(parseInt($(this).attr('dateval')));
        });
        if (pArr[0] < thisDay && thisDay < pArr[1]) return;
        $items.each(function () {
            var $item = $(this);
            $item.removeClass('cen');
            if ($item.hasClass('inout')) $item.removeClass('inout in out').find('span:last').remove();
        });
        if (backFunc) backFunc($this, 'cancel');
    }

    function checkCen() {
        var check = true,
            //inDateVal = parseInt($box.find('.inout.in').attr('dateval')) || false,
            //outDateVal = parseInt($box.find('.inout.out').attr('dateval')) || false;
              inDateVal = parseInt($box.find('.inout.in').attr('dateval') || $sdate.attr('dateval')) || false,
            outDateVal = parseInt($box.find('.inout.out').attr('dateval') || $edate.attr('dateval')) || false;
        if (!inDateVal || !outDateVal) return false;
        $parent.find('>li[dateval]').each(function () {
            var $this = $(this),
                thisVal = parseInt($this.attr('dateval')) || false;
            if (thisVal > inDateVal && thisVal < outDateVal && !$this.hasClass('item')) check = false;
        });
        return check;
    }

    function setCen() {
        //var inDateVal = parseInt($box.find('.inout.in').attr('dateval')) || false,
        //    outDateVal = parseInt($box.find('.inout.out').attr('dateval')) || false,
        inDateVal = parseInt($box.find('.inout.in').attr('dateval') || $sdate.attr('dateval')) || false,
        outDateVal = parseInt($box.find('.inout.out').attr('dateval') || $edate.attr('dateval')) || false;
        i = 0;
        if (!inDateVal || !outDateVal) return;
        $items.each(function () {
            var $this = $(this),
                thisVal = parseInt($this.attr('dateval'));
            //利用时间截, 筛选出入住/离间之间的元素
            if (thisVal > inDateVal && thisVal < outDateVal) {
                $this.addClass('cen');
                i++;
            }
        });
        return i;
    }
}
//初始化已选中的样式
function hotelInitDateStates($box, inputs) {
    var $items = $box.find('.item'),
        $sdate = false,
        sdate = '',
        $edate = false,
        edate = '';
    if (inputs) {
        $sdate = $(inputs[0]);
        $edate = $(inputs[1]);
        var inDateval = parseInt($sdate.attr('dateval')) || false,
              outDateval = parseInt($edate.attr('dateval')) || false;
        $items.each(function () {
            var $this = $(this),
                 thisVal = parseInt($this.attr('dateval')) || false;
            if (thisVal == inDateval) {
                $this.addClass('inout in').find('p').prepend('<span class="txt">入住</span>');
            }
            else if (thisVal == outDateval) {
                $this.addClass('inout out').find('p').prepend('<span class="txt">离店</span>');
            }
            else if (outDateval && (thisVal > inDateval && thisVal < outDateval)) {
                $this.addClass('cen');
            }
        });
    }
}
function hotelCheckDays($box, $sdate) {
    var $parent = $box.find('.day'),
        startDateval = parseInt($sdate.attr('dateval')) || false,
        startStasts = ($sdate.attr('cStasts') == "true") || true;
    if (startDateval && startStasts) {
        $parent.find('>li[dateval]').each(function () {
            var $this = $(this),
                thisVal = parseInt($this.attr('dateval')) || false;
            if (thisVal > startDateval && !$this.hasClass('item')) startStasts = false;
        });
        $sdate.attr('cStasts', startStasts);
    }
}
