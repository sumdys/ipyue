<?php
// 本类由系统自动生成，仅供测试用途
class PublicAction extends Action {
    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        @header("Status: 404 Not Found");
        $this->title="404 错误";
        $this->display("Public:404");
    }
}