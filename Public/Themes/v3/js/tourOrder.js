

$(function () {
    $('html,body').animate({
        scrollTop: 0
    }, 0);
});

(function () {
    //tabs
    $('.areabox').tabs({
        btns: '.naviga-box .area-laber', //所有按钮选择器
        targets: '.areaslist .naviga-city', //所有目标选择器
        model: 1
    });

    //$('html,body').animate('scrollTop', 0);
    var $win = $(window),
        $form = $('#orderForm'),
        isVisa = $('#isVisa').val() || '',
        $customerBox = $('#customerBox'),
        cusCount = parseInt($('#cusCount').val()),
        stock = parseInt($('#stock').val()) || 0,
        pid = $('#aplId').val(),
        $planDate = $('#departureDate') || false,
        planDate = $planDate.length == 0 ? false : ($.newDate($planDate.val())).setHours(00, 00, 00, 00),
        year = $planDate.attr('data-year'),
        month = $planDate.attr('data-month'),
        formOffset = $('.order-form').offset(),
        roomDifference = parseInt($('#roomDifference').val()) || false;
    //scroll
    $win.on('scroll', function () {
        //合计置顶
        if ($win.scrollTop() >= formOffset.top) {
            $('.order-form').addClass('flow');
        } else {
            $('.order-form').removeClass('flow');
        }
    });

    /*表单交互 Begin*/




 

    //表单验证初始化
    //$form.verifyForm({ initialize: true });

    //数字选择器
    $('.form-count').numSelecter();





})();