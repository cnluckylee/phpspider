<?php
/**
 *
 * @copyright   Copyright(c) 2011
 * @author      cnluckylee <cnluckylee@gmail.com>
 * @version     1.0
 */
class spiderController extends Controller {
		protected $model;
        public function __construct() {
                parent::__construct();
                $newmodel = Application::$_spidermodel.'Model';
                $model = new $newmodel;
                $this->model = $model;
        }
    /**
     * 获取分类
     */
    public function getCategory() {
        $this->model->getCategory();

    }
    /**
		 * 全量新增整站
		 */
        public function fulldata() {
         	$this->model->getCategory();
          	$this->model->master();
        }
        /**
         * 全量更新整站
         */
        public function fullupdate() {
        	$this->model->updatefull();
        }
        /**
         * 分布式启动categoryjob
         */
        public function categorymaster()
        {
        	$this->model->master('Category');
        }
        /**
         * 分布式启动itemjob
         */
        public function itemmaster()
        {
        	$this->model->master('Item');
        }
        /**
         * 分布式启动updatemaster
         */
        public function updatemaster()
        {
        	$this->model->master('Update');
        }
        /**
         * 单个categoryjob
         */
        public function categoryjob() {
        	$this->model->CategroyJob();
        }
        /**
         * 单个itemjob
         */
        public function Itemjob() {
         	$this->model->itemJob();
        }
        /**
         * 单个itemjob
         */
        public function Updatejob() {
        	$this->model->updatejob();
        }
        /**
         * 导入商品数据
         * 数组形式传入
         */
        public function importdata()
        {
        	$data = $_POST['data'];
        }
        /**
         * 加入任务
         */
        public function addtask()
        {
        	
        }

        /**
         * categoryretry
         */
        public function retrycategory()
        {
            $this->model->retry('Category');
        }

        /**
         * categoryretry
         */
        public function retryitem()
        {
            $this->model->retry('Item');
        }

    /**
     * 转json
     */
        public function tojson()
        {
            $collection_name = trim(isset($_GET['cname'])?$_GET['cname']:"");
            $this->model->tojson($collection_name);
        }
}

