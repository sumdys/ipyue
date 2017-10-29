<?php

/**
 * User: rainbow
 * Date: 14-4-16
 * Time: 下午16:00
 * To change this template use File | Settings | File Templates.
 */
class AdAction extends CommonAction{
     
	    function _before_index(){
		   $this->relation=true;
	    }
       
	    function adsize(){
	    	$numPerPage=I('numPerPage');//每页显示数量
	    	if($numPerPage==""){$numPerPage=15;}
	    	$pageNum=I('pageNum');//定义当前页
	    	if($pageNum<=1){$pageNum=1;}
	    	$offset=($pageNum-1)*$numPerPage;//步长
	    	$this->numPerPage=$numPerPage;
	    	$this->currentPage=$pageNum;
	    
	    	$this->img=D('AdSize')->limit($offset,$numPerPage)->order('rand desc desc')->select();
	    	$this->display();
	    }
	    
	    //添加广告尺寸
	    function adsizeadd(){
// 	    	if($_POST){	
// 	    		if($_POST['title'] == ''){
// 	    			$this->error('标题不能为空');
// 	    		}
// 	    		if($_POST['width'] == '' && $_POST['height']==''){
// 	    			$this->error('宽度和高度不能为空');
// 	    		}
// 	    			$info=array(
// 	    					'title'        =>$_POST['title'],
// 	    					'width'          =>$_POST['width'],
// 	    					'height'         =>$_POST['height'],
// 	    					'time'  =>time(),
	    			
// 	    			);
// 	    			$res=D('AdSize')->add($info);
// 	    			if($res){
// 	    				$this->success('添加成功');
// 	    			}else{
// 	    				$this->error('添加失败');
// 	    			}
	    		
// 	    	}else{
// 	    		$this->display();
// 	    	}
	    }
    
	    function adsizeedit(){
// 	    	if($_POST){
// 	    		$info=array(
// 	    					'title'        =>$_POST['title'],
// 	    					'width'          =>$_POST['width'],
// 	    					'height'         =>$_POST['height'],
// 	    					'time'  =>time(),
// 	    			);
// 	    		$res=D('AdSize')->where('id='.$_POST['id'])->save($info);
// 	    		if($res){
// 	    			$this->success('修改成功');
// 	    		}else{
// 	    			$this->error('修改失败');
// 	    		}
	    	
// 	    	}else{
// 	    		$id=I('id');
// 	    		$this->info=D('AdSize')->where('id='.$id)->find();
// 	    		$this->display();
// 	    	}
	    }
	 
	    function adsizedel(){
// 	    	if($_POST){
// 	    		$res=D('AdSize')->where('id='.$_POST['id'])->delete();
// 	    		if($res){
// 	    			$this->success('删除成功');
// 	    		}else{
// 	    			$this->error('删除失败');
// 	    		}
// 	    	}else{
// 	    		$id=I('id');
// 	    		$this->info=D('AdSize')->where('id='.$id)->find();
// 	    		$this->display();
// 	    	}
	    	 
	    }

	    function adsizetype(){
	    	
// 	    	$type = M('AdSize')->select;
	    	
// 	    	$this->assign('list', $type);
	    	
	    }
	    
	    function add(){
	    	if($_POST){
	    		if($_POST['title'] == ''){
	    			$this->error('标题不能为空');
	    		}
	    		if($_POST['link'] == ''){
	    			$this->error('链接不能为空');
	    		}
	    		import('ORG.Net.UploadFile');
	    		$upload = new UploadFile();                       // 实例化上传类
	    		$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
	    		$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
	    		$upload->savePath =  './Public/uploads/ad/';   // 设置附件上传目录
	    		$upload->uploadReplace = true;				  //同名则替换
	    		$upload->saveRule = 'uniqid';				 //设置上传头像命名规则(临时图片),修改了UploadFile上传类
	    			
	    		if(!$upload->upload()) {// 上传错误提示错误信息
	    			$this->error($upload->getErrorMsg());
	    		}else{// 上传成功 获取上传文件信息
	    			$info =  $upload->getUploadFileInfo();
	    			$path=$upload->savePath;
	    
	    			$temp_size = getimagesize($path.$info['0']['savename']);

	    			$info=array(
	    					'pic'          =>$info[0]['savename'],
	    					'title'        =>$_POST['title'],
	    					'link'          =>$_POST['link'],
	    					'aid'          =>$_POST['id'],
	    					//'rand'         =>$_POST['num'],
	    					'time'  =>time(),
	    			);
	    			$res=D('Ad')->add($info);
	    			if($res){
	    				$this->success('图片上传成功');
	    			}else{
	    				$this->error('图片上传失败，请重新上传');
	    			}
	    		}
	    	}else{
	    		$this->display();
	    	}
	    }

	    
        function edit(){
        	if($_POST){
        		if($_POST['title'] == ''){
        			$this->error('标题不能为空');
        		}
        		if($_POST['link'] == ''){
        			$this->error('链接不能为空');
        		}
        	  
        		import('ORG.Net.UploadFile');
        		$upload = new UploadFile();                       // 实例化上传类
        		$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
        		$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
        		$upload->savePath =  './Public/uploads/ad/';   // 设置附件上传目录
        		$upload->uploadReplace = true;				  //同名则替换
        		$upload->saveRule = 'uniqid';				 //设置上传头像命名规则(临时图片),修改了UploadFile上传类
        		
        		if(!$upload->upload()) {// 上传错误提示错误信息
        			$this->error($upload->getErrorMsg());
        		}else{// 上传成功 获取上传文件信息
        			$info =  $upload->getUploadFileInfo();
        			$path=$upload->savePath;
        			$temp_size = getimagesize($path.$info['0']['savename']);
        		
        		$info=array(
	    					'pic'          =>$info[0]['savename'],
	    					'title'        =>$_POST['title'],
	    					'link'          =>$_POST['link'],
	    					'aid'          =>$_POST['id'],
	    					//'rand'         =>$_POST['num'],
	    					'time'  =>time(),
	    			);
        		
        		$res=D('Ad')->where('id='.$_POST['id'])->save($info);
        		if($res){
        			$this->success('修改成功');
        		}else{
        			$this->error('修改失败');
        		}
        		}
        	}else{
        		$id=I('id');
        		$this->info=D('Ad')->where('id='.$id)->find();
        		$this->display();
        	}
        }
        
        
        function del(){
        	if($_POST){
        		$res=D('Ad')->where('id='.$_POST['id'])->delete();
        		if($res){
        			$this->success('删除成功');
        		}else{
        			$this->error('删除失败');
        		}
        	}else{
        		$id=I('id');
        		$this->info=D('Ad')->where('id='.$id)->find();
        		$this->display();
        	}
        	
        }
    
}
