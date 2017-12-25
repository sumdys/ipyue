(function () {
    if (!$.cookie('ArrItemInfoString')) $.cookie('ArrItemInfoString', '[]', { path: '/' });
})();
//产品对比
jQuery.nh.compare = ({
    compCount: 3,//可同时进行对比的产品个数, 默认3
    //绑定对选择按钮
    bindChecker: function () {
        if ($('input.compare-checker:checkbox').length == 0) return;
        var compareFunc = this,
            ArrItemInfo = $.parseJSON($.cookie('ArrItemInfoString')),
            $items = $('input.compare-checker:checkbox'),
            ArrItemInfoString = '';
        $items.each(function () {
            var $this = $(this),
                val = $this.val();
            $this.prop('checked', compareFunc.checkItem(val, ArrItemInfo));
        });
        //console.log($items);
        //添加/删除对比列表数据
        $items.on('change', function () {
            var $this = $(this);
            if (!$this.prop('checked')) {
                //删除
                compareFunc.removeItem($this);
            } else {
                //添加
                compareFunc.addItem($this);
            }
        });
    },
    //添加产品到对比盒子
    addItem: function ($btn) {
        var ArrItemInfo = $.parseJSON($.cookie('ArrItemInfoString')), //获取即时cookie
            $num = $('.digital');

        if (ArrItemInfo == null || ArrItemInfo.length < this.compCount) {

            if (ArrItemInfo == null) {
                ArrItemInfo = [];
                i = 0;
            } else {
                i = ArrItemInfo.length;
                i++;
                $num.text(i);//修改数值
                //if (i > 0) num.show();
            }
            //封装对象
            ArrItemInfo.push($.parseJSON('{"id":"' + $btn.val() + '"}'));
            this.updateCookie(ArrItemInfo);
        } else {
            $btn.prop('checked', false);
            alert('每次最多可对比 ' + this.compCount + ' 个产品！');
        }
    },
    //删除对比盒子中的已选项
    removeItem: function ($btn) {
        var ArrItemInfo = $.parseJSON($.cookie('ArrItemInfoString')),//获取即时cookie
        $num = $('.digital');

        //过滤函数过滤数组元素
        ArrItemInfo = $.grep(ArrItemInfo, function (n, i) {
            //过滤条件
            return n.id != ($btn.val() == '' ? $btn.attr('data-val') : $btn.val());
        });

        //console.log(ArrItemInfo);

        if (ArrItemInfo == null) {
            ArrItemInfo = [];
            $num.text(0);//修改数值
        } else {
            $num.text(ArrItemInfo.length);//修改数值
        }
        //console.log(ArrItemInfo);
        this.updateCookie(ArrItemInfo);
    },
    //更新对比cookie
    updateCookie: function (obj) {
        //对象转换成字符串
        var ArrItemInfoString = JSON.stringify(obj);
        //设置，cookie只保存字符串格式
        $.cookie('ArrItemInfoString', ArrItemInfoString, { path: '/' });

        if (obj.length == 0) {
            $('#compare:visible').hide().find('ul.list-ul').html('');
        } else {
            //更新对比盒子数据
            if ($('#compare:visible').length > 0) this.updateBox($('#compare ul.list-ul'), $('#compare .contrast-btn'), obj);
        }

        //if ($('.center').hasClass('compare')) window.location.reload();
    },
    //对比按钮
    binBtn: function () {
        var compareFunc = this,
            ArrItemInfo = $.parseJSON($.cookie('ArrItemInfoString')) || false,
            num = $('.digital').text(ArrItemInfo ? ArrItemInfo.length : 0);

        $('.vs').off('click').on('click', function () {
            var $box = compareFunc.createBox(),
                $listBox = $box.find('ul.list-ul');
            ArrItemInfo = $.parseJSON($.cookie('ArrItemInfoString'));//获取即时cookie

            //无对比产品
            if (!ArrItemInfo || ArrItemInfo.length == 0) {
                alert('请先选择要对比的产品');
                return false;
            }
            //对比盒子已打开
            if ($box.is(':visible')) return false;

            $listBox.html('加载中...');
            $box.show();

            compareFunc.updateBox($listBox, $box.find('.contrast-btn'), ArrItemInfo);
            //$box.show();

            return false;
        });
    },
    //获取已添加到对比盒子内容的产品信息
    updateBox: function ($listBox, $btn, ArrItemInfo, backFunc) {
        var compareFunc = this,
            idObj = this.explainItemInfo(ArrItemInfo),
            compareUrl = '/public/compare?';

        $btn.removeAttr('href');

        $.ajax({
            url: '/public/Compare/compare_pro',
            type: "GET",
            dataType: 'json',
            data: {
                idObj: JSON.stringify(idObj)
            },
            success: function (reObj) {
                //console.log(reObj);
                if (reObj.Code == 0) {
                    var html = '';
                    $.each(reObj.DataList, function (i, d) {
                        html += '<li>'
                            + '<img src="' + d.ImageUrl + '" />'
                            + '<div class="txt">'
                            + '<h3>' + d.ProductName + '</h3>'
                            + '<span class="price">￥' + d.FloorSalePrice + '起</span>'
                            + '<input type="button" class="remove" data-val="' + d.ProductId + '_' + d.GradeID + '" />'
                            + '</div>'
                            + '</li>';
                    });
                    $listBox.html(html);

                    //绑定删除按钮事件
                    $listBox.find('.remove').on('click', function () {
                        if (confirm('是否要删除该产品对比')) {
                            var $this = $(this);
                            compareFunc.removeItem($this);
                            $this.closest('li').remove();
                            $('.compare-checker[value="' + $this.attr('data-val') + '"]').prop('checked', false);
                    }
                    });

                    if (backFunc) backFunc();
                }
            }
        });

        $.each(idObj, function (i, d) {
            if (i != 0) compareUrl += '&';
            compareUrl += 'id' + (i + 1) + '=' + d.pid + '&agr_id' + (i + 1) + '=' + d.gid;
        });
        $btn.attr('href', compareUrl);

    },
    //解释cookie中的对比用id
    explainItemInfo: function (obj) {
        var aggregate = [];
        $.each(obj, function (i, d) {
            var array = (d.id).split('_');
            aggregate.push({ pid: array[0], gid: array[1] });
        });
        if (aggregate.length > 0) return aggregate;
        else return [];
    },
    //检查已添加到对比盒子的项, 并自动选中
    checkItem: function (id, ArrItemInfo) {
        var state = false;
        if (ArrItemInfo && ArrItemInfo.length > 0) {
            $.each(ArrItemInfo, function (i, d) {
                if (d.id == id) state = true;
            });
        }
        return state;
    },
    //生成对比盒子HTML
    createBox: function () {
        if ($('#compare').length == 0) {
            var html = '<div id="compare" class="fix-contrast">'
            + '    <span class="close">隐藏<em class="icon-a"></em></span>'
            + '    <h2>对比栏</h2>'
            + '    <ul class="list-ul"></ul>'
            + '    <a href="" class="contrast-btn" target="_blank">对比</a>'
            + '</div>';
            $('body').append(html);
            $('#compare .close').on('click', function () {
                $(this).parent().hide();
            });
            $('#compare .contrast-btn').on('click', function () {
                if ($('#compare ul.list-ul li').length < 2) {
                    alert('最少选择两个产品才能进行对比.');
                    return false;
                }
            });
        }

        return $('#compare');
    }
});