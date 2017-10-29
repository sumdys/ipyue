// JavaScript Document
$(function() {

    //通用TAB
    $('#list_nav a').click(function() {
        var i = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $('#list_con .list_block').eq(i).addClass('on').siblings().removeClass('on');
    });

    //我的收藏TAB
    $('#gift_list_nav a').click(function() {
        var i = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $('#gift_list .gift_list_block').eq(i).addClass('gift_on').siblings().removeClass('gift_on');
    });

    //我的收藏 礼品更换背景
    $('#gift_list li').mouseover(function() {
        $(this).addClass('bg').siblings().removeClass('bg');
        $(this).find('.delcollect').show();
    }).mouseout(function() {
        $(this).removeClass('bg');
        $(this).find('.delcollect').hide();
    });

    //购买车TAB
    $('#cart_nav a').click(function() {
        var i = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $('#cart .cart_block').eq(i).addClass('cart_on').siblings().removeClass('cart_on');
    });

    //购买车TAB
    $('#exch_nav a').click(function() {
        var i = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $('#exchange .exch_block').eq(i).addClass('exch_on').siblings().removeClass('exch_on');
    });

    $(document).on("keyup",".first_name,.last_name",function(){
        var val=$(this).val().toUpperCase();
        $(this).val(val);
    });

});

//出生年月日
function optionDate(idYear, idMonth, idDay) {
    var htmlYear = "", htmlMonth = "", htmlDay = "";
    var y = $(idYear).attr('on'), m = $(idMonth).attr('on'), d = $(idDay).attr('on');
    for (var i = 1900; i <= 2100; i++) {
        if (i == y) {
            htmlYear += '<option selected="selected" value="' + i + '">' + i + '</option>'
        } else {
            htmlYear += '<option value="' + i + '">' + i + '</option>'
        }
    }

    for (var j = 1; j <= 12; j++) {
        if (j == m) {
            htmlMonth += '<option selected="selected" value="' + j + '">' + j + '</option>'
        } else {
            htmlMonth += '<option value="' + j + '">' + j + '</option>'
        }
    }

    for (var k = 1; k <= 31; k++) {
        if (k == d) {
            htmlDay += '<option selected="selected" value="' + k + '">' + k + '</option>'
        } else {
            htmlDay += '<option value="' + k + '">' + k + '</option>'
        }
    }
    $(idYear).html(htmlYear);
    $(idMonth).html(htmlMonth);
    $(idDay).html(htmlDay);
}
function passenger_form_check(){
    var country_id = $('#country_id').val();
    var card_number = $('#card_number').val();
    var parrent =/^1[45][0-9]{7}|G[0-9]{8}|E[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+$/;
    if(country_id < 0){
        alert('请选择国籍');return false;
    }
    if(!parrent.test(card_number)){
       alert('请输入正确的证件号码');return false;
    }
    return true;
}