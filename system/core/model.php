<?php
/**
 * 核心控制器类
 * @copyright   Copyright(c) 2012
 * @author      cnluckylee <cnluckylee@gmail.com>
 * @version     1.0
 */
class Model {
	protected $db = null;//mysql 
	protected $mongodb = null;//mongodb
	protected $redis = null;//redis
	protected $curlmulit = null;
	protected $pools = null;//数据池
	protected $spidername = null;//spider名称
	protected $spider = null;//spider配置
    protected $log = null;
    protected $spiderrun = 1;//spider运行控制
	protected $maxjobs = 10;//最大运行数
	protected $runpages = 2;//最大每次抓取页面数
	final public function __construct() {
		header ( 'Content-type:text/html;chartset=utf-8' );
		// 初始化mysql
		
		if(isset(Application::$_config ['db']['mysql']) && Application::$_config ['db']['mysql'])
		{
			$this->db = $this->load('mysql');
			$config_db = Application::$_config ['db']['mysql'];
			$this->db->init( $config_db['host'], $config_db['dbuser'], $config_db['dbpwd'], $config_db['dbname'], $config_db['db_charset'], $config_db['timeout'] ); //初始话数据库类
		}
		/*
		 * $this->db = $this->load('mysql'); 
		 * $config_db = Application::$_config ['db']['mysql'];
		 * $this->db->init( $config_db['db_host'], $config_db['db_user'], $config_db['db_password'], $config_db['db_database'], $config_db['db_conn'], $config_db['db_charset'] ); //初始话数据库类
		 */
		// 初始化mongodb
		
		$this->mongodb = $this->load ( 'HMongodb', true );
		
		$config_mongodb = Application::$_config ['db']['mongodb'];
		
		$this->mongodb->init ( $config_mongodb ['host'] . ':' . $config_mongodb ['port'] );
		
		$this->mongodb->selectDb ( $config_mongodb ['dbname'] );

        if(isset(Application::$_config ['db']['mongodbsec']) && Application::$_config ['db']['mongodbsec'])
        {
            $this->mongodbsec =  $this->load ( 'HMongodb', true);
            $config_mongodbsec = Application::$_config ['db']['mongodbsec'];

            $this->mongodbsec->init ( $config_mongodbsec ['host'] . ':' . $config_mongodbsec ['port'] );

            $this->mongodbsec->selectDb ( $config_mongodbsec ['dbname'] );

        }

		// 初始化redis
		$this->redis = $this->load ( 'SRedis', true );	
		$this->curlmulit = new CurlMulit ();
		$this->pools = $this->load ( 'pools', true );
		$this->pools->init ( $this->redis );
		$this->spidername = Application::$_spidername;
		$this->spider = Application::$_spider;
		$this->log = $this->load ( 'log');
		$this->log->init($this->mongodb,$this->spidername);
		$this->maxjobs = Application::$_process;
	}
	/**
	 * 根据表前缀获取表名
	 *
	 * @access final protected
	 * @param string $table_name
	 *        	表名
	 */
	final protected function table($table_name) {
		$config_db = $this->config ( 'db' );
		return $config_db ['db_table_prefix'] . $table_name;
	}
	/**
	 * 加载类库
	 *
	 * @param string $lib
	 *        	类库名称
	 * @param Bool $my
	 *        	如果FALSE默认加载系统自动加载的类库，如果为TRUE则加载自定义类库
	 * @return type
	 */
	final protected function load($lib, $my = FALSE) {
		if (empty ( $lib )) {
			trigger_error ( '加载类库名不能为空' );
		} elseif ($my === FALSE) {
			return Application::$_lib [$lib];
		} elseif ($my === TRUE) {
			return Application::newLib ( $lib );
		}
	}
	/**
	 * 加载系统配置,默认为系统配置 $CONFIG['system'][$config]
	 *
	 * @access final protected
	 * @param string $config
	 *        	配置名
	 */
	final protected function config($config = '') {
		return Application::$_config [$config];
	}
}


