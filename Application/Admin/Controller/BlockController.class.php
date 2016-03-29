<?php
namespace Admin\Controller;
use Think\Controller;
class BlockController extends Controller {
	protected $block;
	public function __construct()
	{
		parent::__construct();
		$this->block = M('block');
	}
    public function index(){
    	$page = I('get.page','','htmlspecialchars'); 
    	$pages = $this->block->order("addtime desc")->page($_GET['p'].',15')->select();
    	$this->assign('pages',$pages);
    	$count = $this->block->count();
		$Page  = new \Think\Page($count,15);
		$show  = $Page->show();
		$this->assign('pager',$show);
        $this->display('index');
    }
    public function addBlock()
    {
    
    	$id = I('get.id','','htmlspecialchars'); 
    	if($id){
    		$pageic = $this->block->where("id=$id")->find();
            $pageic['picss'] = explode(',',$pageic['pic']);
    		$this->assign('p',$pageic);
    		$this->assign('id',$id);
    	}
    	$this->display('add');
    }
      public function delBlock()
    {
    
    	$id = I('get.id','','htmlspecialchars'); 
    	if($id){
    		$result = $this->block->where("id=$id")->delete();
    		if($result){
    			$this->success('删除成功','/jx/admin/Block');
    		}
    	}
    
    }

    public function doAdd()
    {
    
    	$id = I('post.id','','htmlspecialchars'); 
    	$page = $this->block->create();
    	if(intval($id) <= 0)
    		$this->block->addtime = time();
    	$this->block->mdtime = time();
    	if($_FILES['photo1']['size'] > 0 or $_FILES['photo2']['size'] > 0 or $_FILES['photo3']['size'] > 0 or $_FILES['photo4']['size'] > 0 or $_FILES['photo5']['size'] > 0){
	    	$upload = new \Think\Upload();// 实例化上传类
		    $upload->maxSize   =     3145728 ;// 设置附件上传大小
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     '/usr/local/zend/apache2/htdocs/jx/Public/Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'page/'; // 设置附件上传（子）目录
		    $info   =   $upload->upload();
		    if(!$info) {
		        $this->error($upload->getError());
		    }else{// 上传成功
		    	 $pics = array();
		         foreach($info as $file){
				    $pics[] = $file['savepath'].$file['savename'];
				 }
				 $this->block->pic = implode(',', $pics);
				
		    }
		}
    	if($page){
    		if(intval($id) >0){
    			$result = $this->block->save();
    		}else{
    			$result = $this->block->add(); // 写入数据到数据库 
    		}
		    if($result){
		        // 如果主键是自动增长型 成功后返回值就是最新插入的值
		        $this->success('操作成功', '/jx/admin/Block');
		    }
    	}
    
    }

}