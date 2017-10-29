/*
 *
 * jQuery.nh.order for pc v1.0
 * 
 *
 * 
 * 
 *
 */
$.nh.order = {

    tourArea: parseInt($('#tourArea').val()) || false,//测试,1为港澳
    //检查人员比例及人数
    checkPeopleCount: function ($input, stock) {
        var val = parseInt($input.val()),
            newCount = this.getPeopleCount(),
            $cusCount = $('#cusCount'),
            cusCount = parseInt($cusCount.val()),
            title = $input.attr('title'),
            type = $input.attr('data-type'),
            $silbling = $('#customerBox .' + type),
            sbLen = $silbling.length;
        //console.log(this.interaction);
        if (newCount > cusCount) {
            if (!checkProportion()) {
                $input.val(val - 1);
                return false;
            }
            //加
            if (this.interaction) {
                $input.val(val - 1).attr({ 'data-val': val - 1, 'data-count': val - 1 });
                return false;
            }
            if (newCount > 15) {
                $input.val(val - 1).attr({ 'data-val': val - 1, 'data-count': val - 1 });
                alert('一张单最多允许15人报名，如超出人数请分开下单');
                return false;
            }
            if (newCount > stock) {
                $input.val(val - 1).attr({ 'data-val': val - 1, 'data-count': val - 1 });
                alert('剩余位置不足，请选择其他出发日期或咨询客服');
                return false;
            }
            //增加表单
            if (sbLen < val) {
                this.addCustomerForm(sbLen + 1, newCount, type, title);
            }
        } else {
            //减
            if (this.interaction) {
                $input.val(val + 1).attr({ 'data-val': val + 1, 'data-count': val + 1 });
                return false;
            }
            if (!checkProportion()) {
                $input.val(val + 1).attr({ 'data-val': val + 1, 'data-count': val + 1 });
                return false;
            }
            //删除表单
            if (sbLen > val) {
                this.delCustomerForm($silbling.last());
            }
        }
        $cusCount.val(newCount);
        return true;


    },

    getPeopleCount: function () {
        var count = 0;
        $('.demand-sum').each(function () {
            count += parseInt($(this).val());
        });
        return count;
    },
  
    //日期选择
    dateSelecter: function ($dateInput, nowDate, startDate, endDate) {
        var orderFunc = this;
        $dateInput.each(function () {
            var $this = $(this),
                plan = $this.attr('data-plan'),
                planArr = !plan ? [] : plan.split(','),
                days = [0, 1, 2, 3, 4, 5, 6];

            if (planArr && planArr.length > 0) {
                startDate = planArr[0];
                endDate = planArr[planArr.length - 1];
            }

            $this.prop('readonly', true);
            $this.datetimepicker({
                language: 'zh-CN',
                initialDate: startDate || nowDate,
                format: 'yyyy-mm-dd',
                startView: 'month',
                autoclose: true,
                todayBtn: false,
                minView: 2,
                startDate: startDate || nowDate,
                endDate: endDate || null,
                //daysOfWeekDisabled: days
                onRender: function (date, dateVal) {
                    if (planArr && planArr.length > 0) {
                        var hasPlan = ' disabled';
                        $.each(planArr, function (i, d) {
                            //console.log($.newDate(d).valueOf() , dateVal);
                            if ($.newDate(d).valueOf() == dateVal) hasPlan = false;
                        });
                        return hasPlan;
                    } else {
                        return false;
                    }
                    //return ev.date.valueOf() < date - start - display.valueOf() ? ' disabled' : '';
                }
            }).on('changeDate', function (event) {
                return false;
                var $this = $(this);
                $this.trigger('focusout').datetimepicker('hide');
            });

        });
    },
  
    //计价
  /*  updatePayment: function () {
        var $tour = $('.demand-sum'),
            $additional = $('.additional-box .additional'),
            $discount = $('.discount-box input:radio[data-name="discount"]:checked,.discount-box input#coupon'),
            payment = {
                tour: 0,
                additional: 0, //附加费
                discount: 0
            },
            countPayment = 0;
            actPayment = 0;
        if (!$tour || $tour.length == 0) return;

        //费用
        payment.tour = this.createPaymentHtml($tour, 'Payment', $('#peoplePayCount'), 0);
    
        //console.log(payment);

        //合计
        $('.count-payment').text(payment.tour + payment.additional);
   
        countPayment = payment.tour - payment.discount + payment.additional;
        countPayment = countPayment < 0 ? 0 : countPayment;

        //显示
        $('.count-payment').text(countPayment);
        $('.actual-payment').text(countPayment);
    },
    //生成结算HTML
    createPaymentHtml: function ($items, valType, $htmlBox, model) {
        var html = '',
            valCount = 0;
   
        $items.each(function () {
            var $this = $(this),
                val = parseInt($this.attr('data-val')),
                count = parseInt($this.attr('data-count')),
                payment = parseInt($this.attr('data-price'));

            if (!count || count == 0) return;

            if (model == 1) html += '<em class="icon-a"></em>';
            if (model == 2) html += '<em class="icon-b"></em>';

            html += '<div class="form-plate"><label>' + $this.attr('title') + '</label><div class="price ' + (model == 2 ? 'buletxt' : '') + '">';
            if (model == 0) {
                html += '<i class="numBer">' + count + ' X ¥</i>';
                valCount += count * payment;
            } else {
                html += '<i class="numBer">¥</i>';
                valCount += payment;
            }
            html += '<span>' + payment + '</span></div></div>';
        });
        $htmlBox.html(html);
        return valCount;
    } */

};
