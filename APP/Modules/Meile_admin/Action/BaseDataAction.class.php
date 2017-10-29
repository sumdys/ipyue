<?php
/**
 * 基础数据管理
 */
class BaseDataAction extends CommonAction{

    function index(){
     //  echo $this->modelName;
    }

    /*
     * 共公
     */
    function act(){
        $act=I('get.act');
        $this->act=$act;
        if($act=="add"){//添加
            if(IS_POST)
                parent::insert();
            else
                $this->display( $this->modelName."Edit");
        }elseif($act=="edit"){//编辑
            if(IS_POST)
                parent::update();
            else
                parent::edit($this->modelName."Edit");
        }elseif($act=="del"){//删除
            parent::foreverdelete();
        }else{
            parent::index();
        }
    }



    /*
     * 会员等级
     */
    function memberRank(){
        $this->modelName="MemberRank";
        $act=I('get.act');
        $this->act=$act;
        if(IS_POST)
            $_POST['privilege']=json_encode(I('privilege'));
        $this->privilege=D("MemberPrivilege")->order("sort")->select();

        if($act=="add"){//添加
            if(IS_POST){
                parent::insert();
            }else{
                $this->display("MemberRankEdit");
            }
        }elseif($act=="edit"){//编辑
            if(IS_POST){
                parent::update();
            }else{
                parent::edit(false);
                $vo=$this->vo;
                $pr=json_decode($vo['privilege']);
                $privilege= $this->privilege;
                foreach($privilege as $key=>$val){
                    if(in_array($val['id'],$pr))
                        $privilege[$key]['ch']="checked";
                }
                $this->privilege=$privilege;
                $this->display("MemberRankEdit");
            }
        }elseif($act=="del"){//删除
            parent::foreverdelete();
        }else{
            parent::index();
        }
    }

    /*
     * 会员特权
     */
    function memberPrivilege(){
        $this->modelName="MemberPrivilege";
        $act=I('get.act');
        $this->act=$act;
        if($act=="add"){//添加
            if(IS_POST)
                parent::insert();
            else
            $this->display("memberPrivilegeEdit");
        }elseif($act=="edit"){//编辑
            if(IS_POST)
                parent::update();
            else
                parent::edit("memberPrivilegeEdit");
        }elseif($act=="del"){//删除
            parent::foreverdelete();
        }else{
            parent::index();
        }
    }

    /*
     * 分司
     */
    function company(){
        $this->modelName="company";
        $this->act();
    }

    /*
     * 部门
     */
    function department(){
        $this->modelName="department";
        $this->act();
    }




}
