function getCalendar(year, month, backFunc) {

    if ($.type(month) == 'string' && month.length == 2) {
        if (month.indexOf('0') == 0) {
            month = month.substring(1, 2);
        }
    }

    var $calendarBox = $('.calendarbox .day'),
        $parent = $calendarBox.closest('.calendar'),
        planObj = getPlanObj(year, month);
    if (!planObj || planObj.data != null) {
        createCalendar(year, month);
        if (backFunc) backFunc($parent);
    } else {
        $.ajax({
            type: 'GET',
            url: $parent.attr('data-url'),// '/Themes/v3/js/complexMonth.html',
            data: {},
            dataType: 'text',
            success: function (reObj) {
                reObj = $.parseJSON(reObj);
                //console.log(reObj);
                if (reObj.length == 0) {
                    alert('该产品暂无计划');
                    return false;
                }
                //console.log(reObj);
                $.each(reObj, function (i, d) {
                    updatePlanObj(d);
                });
                //console.log($.planObj[0]);
                createCalendar(parseInt(year), parseInt(month));
                if (backFunc) backFunc($parent);
            }//success
        });// $.ajax
    }

    function createCalendar(y, m) {

        planObj = getPlanObj(y, m);
        if (planObj.length == 0) {
            y = parseInt($.planObj[0].year);
            m = parseInt($.planObj[0].month);
            planObj = getPlanObj(y, m);
        }

        $calendarBox
            .removeClass('initialized')
            .nhCalendar({
                year: y,
                month: m,
                dayOnClickBackFunc: function () {
                    //日期被点击时事件
                },
                setCalendarBackFunc: function (y, m, $box) {
                    var $items = $box.find('li.date');
                    $parent.find('.date-nav span').html(y + '年' + m + '月' + '<em></em>');
                    if (!$parent.attr('data-url')) return;
                    //console.log('a:'+planObj.data != null);
                    createHtml(planObj, $items);
                    setCalendarBtn($parent);
                }
            });
    }

    function getPlanObj(y, m) {
        var planObj = [];
        y = parseInt(y, 10);
        m = parseInt(m, 10);
        if ($.planObj.length > 0) {
            //console.log($.planObj);
            $.each($.planObj, function (i, d) {
                //console.log(parseInt(d.year, 10), parseInt(d.month, 10), y, m, parseInt(d.year, 10) == y , parseInt(d.month, 10) == m);
                if (parseInt(d.year, 10) == y && parseInt(d.month, 10) == m) {
                    //console.log(d);
                    planObj = d;
                    return;
                }
            });
        }
        return planObj;
    }

    function updatePlanObj(obj) {
        var unique = $.grep($.planObj, function (d, i) {
            return parseInt(d.year, 10) == parseInt(obj.year, 10) && parseInt(d.month, 10) == parseInt(obj.month, 10);
        });
        if (unique.length == 0) $.planObj.push(obj);
    }

    function createHtml(obj, $items) {
        //console.log(obj);
        var $planAjnID = $('#planAjnID') || false,
            jid = $planAjnID.val() || false;
        $.each(obj.data, function (i, d) {
            if (d.stock != '0') {
                var day = parseInt(d.day, 10),
                    html = '', linkUrl = d.linkUrl,
                    sameDayBoxLen = $items.filter('.item[day="' + day + '"]').length;
                if (sameDayBoxLen == 0 || (sameDayBoxLen > 0 && (jid && parseInt(jid) == d.jid))) {
                    html += '<div class="icon">';
                    if (d.isShiDing) html += '<span class="try"></span>';
                    if (d.discountList && d.discountList.length > 0) {
                        $.each(d.discountList, function (ii, dd) {
                            html += '<span class="' + dd.type + '">' + dd.title + '</span>';
                        });
                    }
                    html += '</div>';
                    html += '<a href="' + linkUrl + '#bookbox"><em>' + day + '</em><p><font class="remainder">余位' + d.stock + '</font><font class="price">¥' + d.price + '</font></p></a>';
                    $items.filter('[day="' + day + '"]').addClass('item').html(html);
                }
            }
        });
    }

    function setCalendarBtn($btnBox) {
        //console.log($btnBox);
        var $c = $btnBox.find('.calendarbox .day'),
            year = parseInt($c.attr('data-year'), 10),
            month = parseInt($c.attr('data-month'), 10),
            $btnLeft = $btnBox.find('.left-btn'),
            $btnRight = $btnBox.find('.right-btn'),
            offBtnClass = 'off',
            planObjLen = $.planObj.length - 1,
            index = 0;
        if (planObjLen <= 0) {
            $btnLeft.addClass(offBtnClass);
            $btnRight.addClass(offBtnClass);
            return;
        }
        $.each($.planObj, function (i, d) {
            if (year == d.year && month == d.month) {
                index = i;
            }
        });
        if (index == 0) {
            $btnLeft.addClass(offBtnClass);
            $btnRight.removeClass(offBtnClass);
        }
        if (index == planObjLen) {
            $btnLeft.removeClass(offBtnClass);
            $btnRight.addClass(offBtnClass);
        }
        if (planObjLen == 0) {
            $btnLeft.removeClass(offBtnClass);
            $btnRight.removeClass(offBtnClass);
        } else if (index > 0 && index < planObjLen) {
            $btnLeft.removeClass(offBtnClass);
            $btnRight.removeClass(offBtnClass);
        }

        $btnLeft.off('click').on('click', function () {
            if ($(this).hasClass(offBtnClass)) return;
            index--;
            //if (month < 1) {
            //    year--;
            //    month = 12;
            //}
            getCalendar($.planObj[index].year, $.planObj[index].month, function () {
                if (backFunc) backFunc($btnBox);
            });
        });
        $btnRight.off('click').on('click', function () {
            if ($(this).hasClass(offBtnClass)) return;
            index++;
            //if (month > 12) {
            //    year++;
            //    month = 1;
            //}
            //console.log($.planObj[index]);
            getCalendar($.planObj[index].year, $.planObj[index].month, function () {
                if (backFunc) backFunc($btnBox);
            });
        });
    }
}

function checkDateLimit(days, limit) {
    if (limit <= 1) return true;
    if (days == limit) {
        return true;
    } else {

        return false;
    }
    //if (limit > 0 && days > 0 && days < limit) {
    //    return false;
    //} else {
    //    return true;
    //}
}