<?php

/**
 * User: rainbow
 * Date: 14-5-6
 * Time: 下午09:12
 * To change this template use File | Settings | File Templates.
 */

class SpecialAction extends CommonAction{
        
        function index(){
             if(I('so')){
                    if(strstr(I('so'),':')){
                        $so=explode(':',I('so'));
                        $map[$so[0]]=$so[1];
                    }else{
                        $where['from_city'] = array('like',"%".I('so')."%");
                        $where['to_city'] = array('like',"%".I('so')."%");
                        $where['status']  = array('like',"%".I('so')."%");
                        $where['is_new']  = array('like',"%".I('so')."%");
                        $where['_logic'] = 'or';
                        $map['_complex'] = $where;
                    }
             }
                $this->map = $map;
                parent::index(D('Special'));
	    	$this->display();
        
    }
       
         //增加航班
          function add(){
              if($_POST){
                
                  if($_POST['from_city']==''){
                      $this->error('出发城市不能为空');
                  }
                   if($_POST['to_city']==''){
                      $this->error('到达城市不能为空');
                  }
                  if($_POST['price']==''){
                      $this->error('价格不能为空');
                  }
                
                    import('ORG.Net.UploadFile');
					$upload = new UploadFile();                       // 实例化上传类
					$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
					$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
					$upload->savePath =  './Public/uploads/cheap/';   // 设置附件上传目录
					$upload->uploadReplace = true;				  //同名则替换
					$upload->saveRule = 'uniqid';				 //设置上传头像命名规则(临时图片)
			
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());		
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$path=$upload->savePath;
				$temp_size = getimagesize($path.$info['0']['savename']);				
                        }
                          $info=array(
					                   'img'          =>$info[0]['savename'],
                                       'title'        =>$_POST['title'],
					                   'from_city'   =>$_POST['from_city'],
					                   'to_city'     =>$_POST['to_city'],
					                   'travel_time' =>$_POST['travel_time'],
                                        'air'     =>$_POST['air'],
                                        'price'     =>$_POST['price'],
                                        'status'     =>$_POST['status'],
                                        'uid'  =>   $_SESSION['uid'],
                                        'is_new'     =>$_POST['is_new'],
					                    'create_time'  =>time()
				       );	
                 
                $Ch = D('Special');
				if(!$Ch->create($info)){
	    				$this->error($Ch->getError());
	    			}
	    			$res=$Ch->add();
				if($res){
					$this->success('添加成功');
				}else{
					//$this->error('添加失败');
                                    $this->error('图片上传失败，请重新上传'.$Ch->getDbError());
				}	
		}else{
			$this->display();
		}  
          }
          
        //修改特价活动    
        function edit(){
        	if($_POST){
                       if($_FILES['img']['error']==0){ 
                       import('ORG.Net.UploadFile');    
						$upload = new UploadFile();                       // 实例化上传类
						$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
						$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
						$upload->savePath =  './Public/uploads/cheap/';   // 设置附件上传目录
						$upload->uploadReplace = true;				  //同名则替换
						$upload->saveRule = 'uniqid';	
						if(!$upload->upload()) {// 上传错误提示错误信息
							 $this->error($upload->getErrorMsg());
						}else{// 上传成功 获取上传文件信息
								$info =  $upload->getUploadFileInfo();
						$path=$upload->savePath;
						$temp_size = getimagesize($path.$info['0']['savename']);
                        }
                           $info=array(
										'img'          =>$info[0]['savename'],
									    'title'        =>$_POST['title'],
										'from_city'   =>$_POST['from_city'],
										'to_city'     =>$_POST['to_city'],
										'travel_time' =>$_POST['travel_time'],
                                         'air'     =>$_POST['air'],
                                         'price'     =>$_POST['price'],
                                         'status'     =>$_POST['status'],
                                         'uid'  =>   $_SESSION['uid'],
                                        'is_new'     =>$_POST['is_new'],
					                     'update_time'  =>time()
				);	
                       }else{
                           $info=array(
                                        'title'        =>$_POST['title'],
										'from_city'   =>$_POST['from_city'],
										'to_city'     =>$_POST['to_city'],
										'travel_time' =>$_POST['travel_time'],
                                         'air'     =>$_POST['air'],
                                         'price'     =>$_POST['price'],
                                         'status'     =>$_POST['status'],
                                         'is_new'     =>$_POST['is_new'],
                                         'uid'  =>   $_SESSION['uid'],
					                     'update_time'  =>time()
				);	
                       }
        		   
        	        $res=D('Special')->where('id='.$_POST['id'])->save($info);
        		if($res){
        			$this->success('修改成功');
        		}else{
        			$this->error('修改失败');
        		}
                }elseif(I('act')=='del_img'){
                    $this->ajaxReturn( D('Special')->delImg());exit;
                }
                else{
                        $id = $_GET['id'];
        		$this->info=D('Special')->where('id='.$id)->find();
        		$this->display();
        	}
        }
        
         //删除航班
         function del(){
             $res=D('Special')->where('id='.$_GET['id'])->delete();
	    		if($res){
	    			$this->success('删除成功');
	    		}else{
	    			$this->error('删除失败');
	    		} 
         }
}
