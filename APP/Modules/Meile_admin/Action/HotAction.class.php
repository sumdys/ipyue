<?php

/**
 * User: rainbow
 * Date: 14-5-6
 * Time: 下午17:00
 * To change this template use File | Settings | File Templates.
 */
class HotAction extends CommonAction{
      
         function index(){
             if(I('so')){
                    if(strstr(I('so'),':')){
                        $so=explode(':',I('so'));
                        $map[$so[0]]=$so[1];
                    }else{
                        $where['title'] = array('like',"%".I('so')."%");
                        //$where['_logic'] = 'or';
                        $map['_complex'] = $where;
                    }
             }
                $this->map = $map;
                parent::index(D('Hot'));
	    	$this->display();
    }
         
          //增加活动
          function add(){
            
			if($_POST){      
            if($_FILES['pic1']['error']==0 || $_FILES['pic2']['error']==0){ 
            import('ORG.Net.UploadFile');
			$upload = new UploadFile();                       // 实例化上传类
			$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
			$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
			$upload->savePath =  './Public/uploads/hot/';   // 设置附件上传目录
			$upload->uploadReplace = true;				  //同名则替换
			$upload->saveRule = 'uniqid';				 //设置上传头像命名规则(临时图片)
			
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());		
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$path=$upload->savePath;
				$temp_size = getimagesize($path.$info['0']['savename']);				
                        }
                            $filename=substr($_POST['filename'],0,strrpos($_POST['filename'], '.'));              
                            $info=array(
					            'pic1'          =>$info[0]['savename'],
								'pic2'          =>$info[1]['savename'],
					            'title'        =>$_POST['title'],
					            'start_time'   =>$_POST['start_time'],
					            'end_time'     =>$_POST['end_time'],
								'filename'     =>$filename,
					            'description'      =>$_POST['description'],
                                'url'     =>$_POST['url'],
                                'status'     =>$_POST['status'],
                                'uid'  =>   $_SESSION['uid'],
					            'create_time'  =>time()
				            );
                 }else{
							 $filename=substr($_POST['filename'],0,strrpos($_POST['filename'], '.'));         
							  $info=array(
					            'title'        =>$_POST['title'],
					            'start_time'   =>$_POST['start_time'],
					            'end_time'     =>$_POST['end_time'],
					            'description'      =>$_POST['description'],
								'filename'     =>$filename,
                                'url'     =>$_POST['url'],
                                'status'     =>$_POST['status'],
                                'uid'  =>   $_SESSION['uid'],
					            'create_time'  =>time()
				            );
                 }              
                $Ch = D('Hot');
				if(!$Ch->create($info)){
	    				$this->error($Ch->getError());
	    			}
	    			$res=$Ch->add();
				if($res){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}	
		}else{
			$this->display();
		}  
          }
         
         //修改活动    
        function edit(){
        	if($_POST){
                      if($_FILES['pic1']['error']==0 || $_FILES['pic2']['error']==0){ 
                       import('ORG.Net.UploadFile');    
        		        $upload = new UploadFile();                       // 实例化上传类
						$upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
						$upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
						$upload->savePath =  './Public/uploads/hot/';   // 设置附件上传目录
						$upload->uploadReplace = true;				  //同名则替换
						$upload->saveRule = 'uniqid';	
						if(!$upload->upload()) {// 上传错误提示错误信息
							 $this->error($upload->getErrorMsg());
						}else{// 上传成功 获取上传文件信息
								$info =  $upload->getUploadFileInfo();
						$path=$upload->savePath;
						$temp_size = getimagesize($path.$info['0']['savename']);
                        }
                        $filename=substr($_POST['filename'],0,strrpos($_POST['filename'], '.'));     
                        $info=array(
					                    'pic1'         =>$info[0]['savename'],
                                        'pic2'         =>$info[1]['savename'],
										'title'        =>$_POST['title'],
										'start_time'   =>$_POST['start_time'],
										'end_time'     =>$_POST['end_time'],
										 'description'  =>$_POST['description'],
                                         'filename'     =>$filename,
                                         'url'     =>$_POST['url'],
                                         'status'     =>$_POST['status'],
                                         'uid'  =>   $_SESSION['uid'],
					                     'create_time'  =>time()
				);	
                       }else{
                               $filename=substr($_POST['filename'],0,strrpos($_POST['filename'], '.'));             
                               $info=array(
									'title'        =>$_POST['title'],
									'start_time'   =>$_POST['start_time'],
									'end_time'     =>$_POST['end_time'],
									 'description' =>$_POST['description'],
                                     'url'        =>$_POST['url'],
                                     'filename'     =>$filename,
                                    'status'     =>$_POST['status'],
                                     'uid'  =>   $_SESSION['uid'],
					                 'create_time'  =>time()
				);
                       }
        		   
        		     $AdDb=D('Hot');
	    			if(!$AdDb->create($info)){
	    				$this->error($AdDb->getError());
	    			}
	    			$res=$AdDb->where('id='.$_POST['id'])->save($info);
					if($res){
						$this->success('修改成功');
					}else{
						$this->error('修改失败'.$AdDb->getDbError());
					}
					
					}elseif(I('act')=='del_img'){
						$this->ajaxReturn( D('Hot')->delImg());exit;
					}
					else{
							$id = $_GET['id'];
					$this->vo=D('Hot')->where('id='.$id)->find();
					$this->display();
        	}
        }
          
        function del(){
             $res = D('Hot')->where('id='.$_GET['id'])->delete();
             if($res){
                 $this->seccss('删除成功');
             }else{
                 $this->error('删除失败');
             }
              
         }
        
        function hotmodel(){
			import("@.ORG.Util.Dir");
			//$path='D:/xampp/htdocs/newasf/APP/Modules/Home/Tpl/Default/Zt';
			$path=$_SERVER['DOCUMENT_ROOT'].'/newasf/APP/Modules/Home/Tpl/Default/Zt';
			$dir = new Dir($path);
			$list = $dir->_values;
			foreach ($list as $key => $val){
				$list[$key]['filename'] = iconv('gbk', 'utf-8', $list[$key]['filename']);
				$list[$key]['fileimg'] = $this->getFileImg($val);
			}
					//print_r($list);
			$this->assign('list',$list);
			$this->display();
                    
                }         

	//文件图标
	public function getFileImg($ary){
		if(key_exists('type', $ary)){			
			if($ary['type']=='dir'){
				$filename = 'dir';
			}else if($ary['type']=='file'){
				switch ($ary['ext']){
					case 'dir':
						$filename = 'dir';
						break;
					case 'php':
						$filename = 'php';
						break;
					case 'jpg':
						$filename = 'jpg';
						break;
					case 'gif':
						$filename = 'gif';
						break;
					case 'png':
						$filename = 'image';
						break;
					case 'js':
						$filename = 'js';
						break;
					case 'flash':
						$filename = 'flash';
						break;
					case 'css':
						$filename = 'css';
						break;
					case 'txt':
						$filename = 'txt';
						break;
					case 'zip':
						$filename = 'zip';
						break;
					case 'html':
						$filename = 'htm';
						break;
					case 'htm':
						$filename = 'htm';
						break;
					case 'wmv':
						$filename = 'wmv';
						break;
					case 'rm':
						$filename = 'rm';
						break;
					case 'mp3':
						$filename = 'mp3';
						break;						
					default:
						$filename = 'unknow';			
				}
			}
			$fileimg = '<img src="__PUBLIC__/dwz/Images/file/'.$filename.'.gif" align="absmiddle" />';
			return $fileimg;
		}
	}
}
