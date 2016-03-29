<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends Controller {
	protected $news;
    protected $category;
	public function __construct()
	{
		parent::__construct();
		$this->news = M('news');
        $this->category = M('category');
	}
    public function index(){
    	$page = I('get.page','','htmlspecialchars'); 
    	$pages = $this->news->order("addtime desc")->page($_GET['p'].',15')->select();
    	$this->assign('pages',$pages);
    	$count = $this->news->count();
		$Page  = new \Think\Page($count,15);
		$show  = $Page->show();
		$this->assign('pager',$show);
        $this->display('index');
    }
    public function category()
    {
         $category = $this->category->order("addtime desc")->select();
         $this->assign('cats',$category);
         $this->display('category');
    }
    public function addCategory()
    {
        $id = I('get.id','','htmlspecialchars'); 
        if($id){
            $cate = $this->category->where("id=$id")->find();
            $this->assign('p',$cate);
            $this->assign('id',$id);
        }
        $this->display('addCategory');     
    }
    public function delCategory()
    {
    
        $id = I('get.id','','htmlspecialchars'); 
        if($id){
            $result = $this->category->where("id=$id")->delete();
            if($result){
                $this->success('删除成功','/jx/admin/News/Category');
            }
        }
    
    }
    public function doAddCategory()
    {
        $id = I('post.id','','htmlspecialchars'); 
        $page = $this->category->create();
        if(intval($id) <= 0)
            $this->category->addtime = time();
        $this->category->mdtime = time();
        if($page){
            if(intval($id) >0){
                $result = $this->category->save();
            }else{
                $result = $this->category->add(); // 写入数据到数据库 
            }
            if($result){
                // 如果主键是自动增长型 成功后返回值就是最新插入的值    
                $this->success('操作成功', '/jx/admin/News/Category');
            }
        }
    }
    public function addNews()
    {
    
    	$id = I('get.id','','htmlspecialchars'); 
    	if($id){
    		$pageic = $this->news->where("id=$id")->find();
    		$pageic['picss'] = explode(',',$pageic['pic']);
    		$this->assign('p',$pageic);
    		$this->assign('id',$id);
    	}
    	$parentpage = $this->category->select();
    	$this->assign('parentpage',$parentpage);
    	$this->display('add');
    }
      public function delNews()
    {
    
    	$id = I('get.id','','htmlspecialchars'); 
    	if($id){
    		$result = $this->news->where("id=$id")->delete();
    		if($result){
    			$this->success('删除成功','/jx/admin/Page');
    		}
    	}
    
    }

    public function doAdd()
    {
    
    	$id = I('post.id','','htmlspecialchars'); 
    	$page = $this->news->create();
    	if(intval($id) <= 0)
    		$this->news->addtime = time();
    	$this->news->mdtime = time();
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
				 $this->news->pic = implode(',', $pics);
				
		    }
		}
    	if($page){
    		if(intval($id) >0){
    			$result = $this->news->save();
    		}else{
    			$result = $this->news->add(); // 写入数据到数据库 
    		}
		    if($result){
		        // 如果主键是自动增长型 成功后返回值就是最新插入的值
		        $this->success('操作成功', '/jx/admin/News');
		    }
    	}
    
    }

}