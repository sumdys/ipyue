<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-7-23
 * Time: 下午5:32
 * To change this template use File | Settings | File Templates.
 */
class DestinationAction extends Action{
        function index(){
			$this->title='目的地';
        	$this->display();
		}
		
		function destn(){
			$this->title='具体目的地栏目';
        	$this->display();
		}
		
		function guide(){
			$this->title='指南页面';
        	$this->display();
		}
		
		function guide_edit(){
			$this->title='编辑指南页面';
        	$this->display();
		}
		
		function sight(){
			$this->title='景点栏目';
        	$this->display();
		}
		function sight_cont(){
			$this->title='景点内容';
        	$this->display();
		}
		
		function stay(){
			$this->title='住宿';
        	$this->display();
		}
		
		function shopping(){
			$this->title='购物栏目';
        	$this->display();
		}
		
		function shopping_cont(){
			$this->title='购物内容';
        	$this->display();
		}
		
		function journeys(){
			$this->title='线路栏目';
        	$this->display();
		}

}