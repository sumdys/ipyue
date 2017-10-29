<?php
/**
 * User: rainbow
 * Date: 14-4-16
 * Time: 下午16:00
 * To change this template use File | Settings | File Templates.
 */
class AdAction extends CommonAction{
     
	    function _before_index(){
		//   $this->relation=true;
	    }
           //广告分页
           function index(){
            //    $this->adsizetype();
	    	
             if(I('so')){
                    if(strstr(I('so'),':')){
                        $so=explode(':',I('so'));
                        $map[$so[0]]=$so[1];
                    }else{
                        $where['title'] = array('like',"%".I('so')."%");
                        $where['cid']  = array('like',"%".I('so')."%");
                        $where['status']  = array('like',"%".I('so')."%");
                        $where['link']  = array('like',"%".I('so')."%");
                        $where['_logic'] = 'or';
                        $map['_complex'] = $where;
                    }
             }
                $this->map = $map;
                parent::index(D('Ad'));
                $this->assign("li", D("Ad")->category());
	    	$this->display();
	    }
            
	    function category(){
	    	 if(I('so')){
                    if(strstr(I('so'),':')){
                        $so=explode(':',I('so'));
                        $map[$so[0]]=$so[1];
                    }else{
                        $where['title'] = array('like',"%".I('so')."%");
                        $map= $where;
                    }
             }
               $this->map = $map;
                parent::index(D('adCategory'));
                $this->assign("list", D("Ad")->category());
	    	$this->display('category');
	    }
	   
            function insert(){
                //$rs=D("AdCategory")->category();
                $rs=D("Ad")->category();
                if($rs['status']==1){
                    $this->success($rs['info']);
                }else{
                    $this->error($rs['info']);
                }
            }

            function addCategory(){
                    $this->assign("list", D("Ad")->category());
                    $this->display();
            }

            function editCategory(){
                if(IS_POST){
                    $rs=D("Ad")->category();
                    if($rs['status']==1){
                        $this->success($rs['info']);
                    }else{
                        $this->error($rs['info']);
                    }
                }
                $this->assign("list", D("Ad")->category());
                $this->actionName='AdCategory';
            //    $this->edit();
             //   $this->display("addCategory");
               $this->display='addCategory';
                parent::edit();
            }
            
            function _before_edit(){
                $this->assign("list", D("Ad")->category());
            }

            function _before_update(){
                $_POST['published']=strtotime($_POST['published']);
            }

            function category_foreverdelete(){
                   $this->actionName='AdCategory';
                   $this->foreverdelete();
            }
            
            //删除广告尺寸
	    function del_category(){
	    		$res=D('ad_category')->where('cid='.$_GET['cid'])->delete();
	    		if($res){
	    			$this->success('删除成功');
	    		}else{
	    			$this->error('删除失败');
	    		} 
	    }
           
//	    function adsizetype(){
//	    	
//	    	$type = D('ad_category')->order('cid asc')->select();
//	    	
//	    	$this->assign('li', $type);
//	    	
//	    }
	    
              //增加广告
	    function add(){
	     $this->assign("list", D("Ad")->category());
	    	if($_POST){
	    		if($_POST['title'] == ''){
	    			$this->error('标题不能为空');
	    		}
	    		if($_POST['link'] == ''){
	    			$this->error('链接不能为空');
	    		}
                       if($_FILES['img']['error']==0){ 
	    		import('ORG.Net.UploadFile');
	    		$upload = new UploadFile();                       // 实例化上传类
	    		$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
                        
                        //$upload->saveRule = imgsave();
                        $upload->thumb = true;
                        $upload->thumbMaxWidth = '100,300';
                        $upload->thumbMaxHeight = '100,300';

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
                                $_POST['img']= U('/','','','',true)."/Public/uploads/ad/".$info[0]['savename'];
                         }      
	    			$info=array(
	    					'img'          =>$_POST['img'],
	    					'title'        =>$_POST['title'],
	    					'link'          =>$_POST['link'],
                                               'width'          =>$_POST['width'],
                                               'height'          =>$_POST['height'],
	    					'cid'          =>$_POST['cid'],
	    					'status'       =>$_POST['status'],
                                                'content'      =>$_POST['content'],
	    					'time'  =>time()
	    			);
                          }else{
                             $info=array(
	    					'img'          =>$_POST['img'],
	    					'title'        =>$_POST['title'],
	    					'link'          =>$_POST['link'],
                                                'width'          =>$_POST['width'],
                                                'height'         =>$_POST['height'],
                                               'link'          =>$_POST['link'],
	    					'cid'          =>$_POST['cid'],
	    					'status'         =>$_POST['status'],
                                                'content'      =>$_POST['content'],
	    					'time'  =>time()
                             );
                             
                             }
	    			$AdDb=D('Ad');
                  
	    			if(!$AdDb->create($info)){
	    				$this->error($AdDb->getError());
	    			}
	    			$res=$AdDb->add();
	    			if($res){
	    				$this->success('图片上传成功');
	    			}else{
                                    $this->error('图片上传失败，请重新上传'.$AdDb->getDbError());
	    			}
	    		
	    	}else{
                        $id=I('id');
        		$this->info=D('Ad')->where('id='.$id)->find();
	    		$this->display();
	    	}
	    }
           
            
 
	//修改广告    
        function edit(){
              $this->assign("list", D("Ad")->category());
        	if($_POST){
        		if($_POST['title'] == ''){
        			$this->error('标题不能为空');
        		}
        		if($_POST['link'] == ''){
        			$this->error('链接不能为空');
        		}
        	        //print_r($_POST);
                        //print_r($_FILES['img']);
                       if($_FILES['img']['error']==0){ 
        		import('ORG.Net.UploadFile');
        		$upload = new UploadFile();                       // 实例化上传类
        		$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
                        //$upload->saveRule = imgsave();
                        $upload->thumb = true;
                        $upload->thumbMaxWidth = '50,100';
                        $upload->thumbMaxHeight = '50,100';
        		$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
        		$upload->savePath =  './Public/uploads/ad/';   // 设置附件上传目录
        		$upload->uploadReplace = true;				  //同名则替换
        		$upload->saveRule = 'uniqid';				 //设置上传图片命名规则(临时图片),修改了UploadFile上传类	
        		if(!$upload->upload()) {// 上传错误提示错误信息
        			$this->error($upload->getErrorMsg());
        		}else{// 上传成功 获取上传文件信息
        			$info =  $upload->getUploadFileInfo();
        			$path=$upload->savePath;
        			$temp_size = getimagesize($path.$info['0']['savename']);
                                $_POST['img']= U('/','','','',true)."/Public/uploads/ad/".$info[0]['savename'];
                        }
                           $info=array(
	    					//'img'          =>$info[0]['savename'],
                                               	'img'          =>$_POST['img'],
	    					'title'        =>$_POST['title'],
	    					'link'          =>$_POST['link'],
                                                'width'          =>$_POST['width'],
                                                'height'          =>$_POST['height'],
	    					'cid'          =>$_POST['cid'],
	    					'status'         =>$_POST['status'],
                                               'is_url'         =>$_POST['is_url'],
                                                'content'      =>$_POST['content'],
	    					'time'  =>time()
	    	           );
                       }else{
                           $info=array(
                                                'img'        =>$_POST['img'],
	    					'title'        =>$_POST['title'],
	    					'link'          =>$_POST['link'],
                                                'width'          =>$_POST['width'],
                                               'height'          =>$_POST['height'],
	    					'cid'          =>$_POST['cid'],
	    					'status'       =>$_POST['status'],
                                               'is_url'        =>$_POST['is_url'],
                                                'content'      =>$_POST['content'],
	    					'time'  =>time()
	    			); 
                       }
        		   
        		       $AdDb=D('Ad');
	    			if(!$AdDb->create($info)){
	    				$this->error($AdDb->getError());
	    			}
	    			$res=$AdDb->where('id='.$_POST['id'])->save($info);
        		if($res){
        			$this->success('修改成功');
        		}else{
        			$this->error('修改失败'.$AdDb->getDbError());
        		}
        	}else{
        		$id=I('id');
        		$this->info=D('Ad')->where('id='.$id)->find();
        		$this->display();
        	}
        }
        
        //删除广告
        function del(){
        	$res=D('ad')->where('id='.$_GET['id'])->delete();
	    		if($res){
	    			$this->success('删除成功');
	    		}else{
	    			$this->error('删除失败');
	    		} 
        	
         }
        
           //获取js
	    function admodel(){
	    
	    		$id=I('id');
	    		$this->info=D('ad')->where('id='.$id)->find();
	    		$this->display();
	    	
	    }
           

}
