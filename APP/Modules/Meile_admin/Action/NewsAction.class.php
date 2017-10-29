<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-2
 * Time: 下午5:16
 * To change this template use File | Settings | File Templates.
 */
//需求模型
class NewsAction extends CommonAction{
    function _before_index(){
    	$this->relation=true;
    }

    function category(){
	   unset($_POST);
	  $this->assign("list", D("News")->category());
	  $this->display();
    }

    function insert(){
       $rs=D("News")->category();
        if($rs['status']==1){
            $this->success($rs['info']);
        }else{
            $this->error($rs['info']);
        }
    }

    function addCategory(){
       $this->assign("list", D("News")->category());
       $this->display();
    }
   
    function editCategory(){
       if(IS_POST){
            $rs=D("News")->category();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        }
        $this->assign("list", D("News")->category());
        $this->actionName='Category';
        $this->display='addCategory';
        $this->edit();
    }

    function category_foreverdelete(){
       $this->actionName='Category';
           $this->foreverdelete();
    }

    function add(){
       if (IS_POST) {
            $rs=D("News")->addNews();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        } else {
           $this->assign("list", D("News")->category());
            $this->display();
        }
    }

    function _before_edit(){
       $this->assign("list", D("News")->category());
    }

    function _before_update(){
       $_POST['published']=strtotime($_POST['published']);
    }
	
	function ad(){
	    $numPerPage=I('numPerPage');//每页显示数量
			if($numPerPage==""){$numPerPage=30;}
		$pageNum=I('pageNum');//定义当前页 
	        if($pageNum<=1){$pageNum=1;}		
		$offset=($pageNum-1)*$numPerPage;//步长	
		$this->numPerPage=$numPerPage;	
		$this->currentPage=$pageNum;		
		
		$this->img=D('NewsImg')->limit($offset,$numPerPage)->order('rand desc,update_time desc')->select();	
		$this->display();
	}
	
	function ad_add(){
		if($_POST){
			if($_POST['title'] == ''){
				$this->error('标题不能为空');
			}
			if($_POST['src'] == ''){
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
//				if($temp_size[0] < 335 || $temp_size[1] < 160){//判断宽和高是否符合头像要求
//					$this->ajaxReturn(0,'图片宽或高不得小于100px！',0,'json');
//				}
				$info=array(
					'img'          =>$info[0]['savename'],
					'title'        =>$_POST['title'],
					'src'          =>$_POST['src'],
					'rand'         =>$_POST['num'],
					'type'         =>$_POST['type'],
					'create_time'  =>time(),
					'update_time'  =>time()
				);				
				$res=D('NewsImg')->add($info);
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

	function ad_edit(){
		if($_POST){
			if($_POST['title'] == ''){
				$this->error('标题不能为空');
			}
			if($_POST['src'] == ''){
				$this->error('链接不能为空');
			}
			$info=array(
				'title'        =>$_POST['title'],
				'src'          =>$_POST['src'],
				'rand'         =>$_POST['num'],
				'update_time'  =>time()
			);
			$res=D('NewsImg')->where('id='.$_POST['id'])->save($info);
			if($res){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
				
		}else{
			$id=I('id');
			$this->info=D('NewsImg')->where('id='.$id)->find();			
			$this->display();
		}	
	}
	
	function ad_del(){
		if($_POST){			
			$res=D('NewsImg')->where('id='.$_POST['id'])->delete();
			if($res){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}				
		}else{
			$id=I('id');
			$this->info=D('NewsImg')->where('id='.$id)->find();			
			$this->display();
		}	
	
	}	
}?>