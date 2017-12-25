<?php
// 后台用户模块
class MarketingOrderAction extends CommonAction {


    function index(){
        $title=I('post.title');
        $status = I('post.status');
        $start_date = I('post.start_date');
        $end_date = I('post.end_date');
        if($title)
            $map['title']= array('like',"%".$title ."%");
        if($status){
            $map['status']=$status;
        }
        if($start_date){
        	$map['create_time']=array('egt',strtotime($start_date));
        }
        if($end_date){
        	$map['create_time']=array('elt',strtotime($end_date)+24*60*60);
        }
//
        $map['pay_state'] =1;
        $map[]='channel_type is not null';
		//取得满足条件的记录数
        $model= D('TripOrder');
        $group = 'FROM_UNIXTIME(create_time,\'%Y%m%d\'),channel_type';
		$count = $model->where ($map)->group($group)->count();
    //   dump($map);
		if ($count > 0) {
			import ( "@.ORG.Util.Page" );
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )){
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '';
			}
			$p = new Page ( $count, $listRows );
			 $pageNum =empty($_REQUEST['numPerPage']) ? C('PAGE_LISTROWS') : $_REQUEST['numPerPage'];
			 $field = 'FROM_UNIXTIME(create_time,\'%Y-%m-%d\') create_date,count(id) num,count(channel_type) num1';
			//分页查询数据
            if($this->relation){
                 $voList = $model->where($map)->relation($this->relation)->order('create_date DESC')->limit($pageNum)->field($field)->page($_REQUEST[C('VAR_PAGE')])->group($group)->select();
            }else{
	    		 $voList = $model->where($map)->field($field)->order('create_date DESC')->group($group)->limit($pageNum)->page($_REQUEST[C('VAR_PAGE')])->select();
            }
//			   echo M()->getLastSql();exit;
        //    echo $order;
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ){
				if (!is_array ( $val )){
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
//			var_dump($voList);exit;
        //    print_r($voList);
			//分页显示
			$page = $p->show();
			//模板赋值显示
			$this->list=$voList ;
			$this->sort=$sort ;
			$this->order=$order ;
			$this->sortImg=$sortImg ;
			$this->sortType=$sortAlt ;
			$this->page=$page;
		}

		$this->assign ( 'totalCount', $count );
        $this->totalPages = ceil($count/$pageNum);
		//lxz
		 $pageNum =empty($_REQUEST['numPerPage']) ? C('PAGE_LISTROWS') : $_REQUEST['numPerPage'];
		 $this->assign ( 'numPerPage', $pageNum ); //每页显示多少条
		$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
        $this->display();
    }



    function add(){
        if (IS_POST) {
            $rs=D("Freetour")->addNews();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        } else {
            $this->display();
        }
    }

    function edit(){
        if (IS_POST) {
            $data['id']=$_POST['info']['id'];
            $data['brokerage']= $_POST['brokerage'];
            $rs=D("Freetour")->save($data);
            if($rs){
                $this->success('保存成功');
            }else{
                $this->error('保存失败');
            }
        } else {
            $this->info = D("Freetour")->info();
            $this->display();
        }
    }


    function _before_update(){
        $_POST['published']=strtotime($_POST['published']);
    }


}
?>