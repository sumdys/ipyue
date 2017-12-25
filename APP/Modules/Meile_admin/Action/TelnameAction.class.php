<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rainbow
 * Date: 13-8-12
 * Time: 下午6:05
 * To change this template use File | Settings | File Templates.
 */

class TelnameAction extends CommonAction{

       function index(){
       	$map=D("UserAdmin")->userLevelWhere();
       	
             if(I('so')){
                    if(strstr(I('so'),':')){
                        $so=explode(':',I('so'));
                        $map[$so[0]]=$so[1];
                    }else{
                        $where['t_name'] = array('like',"%".I('so')."%");
                        $where['qq'] = array('like',"%".I('so')."%");
                        $where['tel'] = array('like',"%".I('so')."%");
                        $where['status']  = array('like',"%".I('so')."%");
                        $where['_logic'] = 'or';
                        $map['_complex'] = $where;
                    }
             }
                $map['user_id']=getUId();
                $this->map = $map;
                parent::index(D('Telname'));
	    	    $this->display();
        
          }
          
		 //总经理（管理}界面 成功客户订单
		 function customer(){
		 	//$map=D("UserAdmin")->userLevelWhere('id');
		 	$map=D("UserAdmin")->userLevelWhere();
		 	
             if(I('so')){
                    if(strstr(I('so'),':')){
                        $so=explode(':',I('so'));
                        $map[$so[0]]=$so[1];
                    }else{
                        $where['name'] = array('like',"%".I('so')."%");
                        $where['qq'] = array('like',"%".I('so')."%");
                        $where['tel'] = array('like',"%".I('so')."%");
                        $where['status']  = array('like',"%".I('so')."%");
                        $where['_logic'] = 'or';
                        $map['_complex'] = $where;
                    }
             }
               
	        if(I('so_date1')&& I('so_date2')){
	            $map['create_time']=array(array('egt',strtotime(I('so_date1'))),array('elt',strtotime(I('so_date2'))));
	        }
	        $this->map=$map;
	        $this->order='id desc';
	        $this->relation = true;
	        parent::index(D("Telname"));
	        $this->display();
          }
		  
          //增加客户
          function add(){
          	
              if($_POST){	    
              	echo $_POST['telname'];
              	if(strlen($_POST['tel'])<11){
              		$this->error('手机号码不能小于11位');
              	}
                       $info=array(
					                  'b_id'     =>$_POST['b_id'],
					                  't_name'     =>$_POST['t_name'],
					                  'tel'      =>$_POST['tel'],
					                  'phone'    =>$_POST['phone'],
					                  'email'    =>$_POST['email'],
                                       'sex'     =>$_POST['sex'],
                                       'qq'      =>$_POST['qq'],
                       		           'weixin'   =>$_POST['weixin'],
                       		           'address' =>$_POST['address'],
                       		           'content' =>$_POST['content'],
									   'create_time'  =>$_POST['create_time'],
                                       'user_id'  => getUId()
					                
				           );
                   $Tel = D('Telname');
				   if(!$Tel ->create($info)){
	    				$this->error($Tel ->getError());
	    			}
	    			$res=$Tel ->add();
					if($res){
						$this->success('客户信息添加成功');
						
					}else{
						$this->error('客户信息添加失败');
					}	
					}else{
						$this->display();
					}  
          }
         
         //修改客户信息    
        function edit(){
        	if($_POST){
                           $info=array(
					                  'b_id'       =>$_POST['b_id'],
					                  't_name'     =>$_POST['t_name'],
					                  'tel'          =>$_POST['tel'],
					                  'phone'    =>$_POST['phone'],
					                  'email'     =>$_POST['email'],
                                       'sex'       =>$_POST['sex'],
                                       'qq'        =>$_POST['qq'],
                       		           'weixin'   =>$_POST['weixin'],
                       		           'address' =>$_POST['address'],
                       		           'content' =>$_POST['content'],
                                       'user_id'  =>   getUId(),   
					                   'update_time'  =>time()
                           		
				           );
                      
	    		$res=D('Telname')->where('id='.$_POST['id'])->save($info);
        		if($res){
        			$this->success('修改成功');
        		}else{
        			$this->error('修改失败');
        		}
                }
                else{
                $id = $_GET['id'];
        		$this->vo=D('Telname')->where('id='.$id)->find();
        		$this->display();
        	}
        }
        
         //删除航班
         function del(){
             $res=D('Telname')->where('id='.$_GET['id'])->delete();
	    		if($res){
	    			$this->success('删除成功');
	    		}else{
	    			$this->error('删除失败');
	    		} 
         }
         
       
}