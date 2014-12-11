<?php

/**
 * 应用驱动类
 * @copyright   Copyright(c) 2012
 * @author      cnluckylee <cnluckylee@gmail.com>
 * 参数：r：route,a:action
 * @version     1.0
 */
define('SYSTEM_PATH', dirname(__FILE__));
define('ROOT_PATH', substr(SYSTEM_PATH, 0, -7));
define('SYS_LIB_PATH', SYSTEM_PATH . '/lib');
define('Spider_Conf_PATH', ROOT_PATH . '/config/spider');
define('CONFIG_PATCH',ROOT_PATH.'/config');
define('APP_LIB_PATH', ROOT_PATH . '/lib');
define('SYS_CORE_PATH', SYSTEM_PATH . '/core');
define('CONTROLLER_PATH', ROOT_PATH . '/controller');
define('MODEL_PATH', ROOT_PATH . '/model');
define('VIEW_PATH', ROOT_PATH . '/view');
define('LOG_PATH', ROOT_PATH . '/log/');

final class Application {

    public static $_lib = null;
    public static $_config = null;
    public static $_spider = null;
    public static $_spidername = null;
    public static $_spidermodel = 'spider';
    public static $_process = 10;
    public static $_urlparams = null;

    public static function init() {
        self::setAutoLibs();
        require SYS_CORE_PATH . '/model.php';
        require SYS_CORE_PATH . '/controller.php';
        require SYSTEM_PATH . '/elements.php';
        require SYS_CORE_PATH . '/spiderModel.php';
        require MODEL_PATH.'/productModel.php';
        require MODEL_PATH.'/productXModel.php';
        require CONFIG_PATCH.'/spiderConfigFactory.php';
    }

    /**
     * 创建应用
     * @access      public
     * @param       array   $config
     */
    public static function run($config) {
        self::$_config = $config['system'];
        self::init();
        self::autoload();
        self::$_lib['route']->setUrlType(self::$_config['route']['url_type']); //设置url的类型
        $url_array = self::$_lib['route']->getUrlArray();                    //将url转发成数组
        self::routeToCm($url_array);

    }

    /**
     * 自动加载类库
     * @access      public
     * @param       array   $_lib
     */
    public static function autoload() {
        foreach (self::$_lib as $key => $value) {
            require (self::$_lib[$key]);
            $lib = ucfirst($key);
            self::$_lib[$key] = new $lib;
        }

//                 //初始化cache
//                 if(is_object(self::$_lib['cache'])){
//                         self::$_lib['cache']->init(
//                                 ROOT_PATH.'/'.self::$_config['cache']['cache_dir'],
//                                 self::$_config['cache']['cache_prefix'],
//                                 self::$_config['cache']['cache_time'],
//                                 self::$_config['cache']['cache_mode']
//                                 );
//                 }
    }

    /**
     * 加载类库
     * @access      public
     * @param       string  $class_name 类库名称
     * @return      object
     */
    public static function newLib($class_name) {
        $app_lib = $sys_lib = '';
        $app_lib = APP_LIB_PATH . '/' . self::$_config['lib']['prefix'] . '_' . $class_name . '.php';
        $sys_lib = SYS_LIB_PATH . '/lib_' . $class_name . '.php';

        if (file_exists($app_lib)) {
            require ($app_lib);
            $class_name = ucfirst(self::$_config['lib']['prefix']) . ucfirst($class_name);
            return new $class_name;
        } else if (file_exists($sys_lib)) {
            require ($sys_lib);
            return self::$_lib["$class_name"] = new $class_name;
        } else {
            trigger_error('加载 ' . $class_name . ' 类库不存在');
        }
    }

    /**
     * 自动加载的类库
     * @access      public
     */
    public static function setAutoLibs() {
        self::$_lib = array(
            'route' => SYS_LIB_PATH . '/lib_route.php',
            'mysql' => SYS_LIB_PATH . '/lib_mysql.php',
//                 	'mongodb'              =>      SYS_LIB_PATH.'/lib_HMongodb.php',
//                   	'redis'              =>      SYS_LIB_PATH.'/lib_SRedis.php',
            'log' => SYS_LIB_PATH . '/lib_log.php',
            'cache' => SYS_LIB_PATH . '/lib_cache.php',
            'thumbnail' => SYS_LIB_PATH . '/lib_thumbnail.php',
            'curlmulit' => SYS_LIB_PATH . '/lib_CurlMulit.php',
        );
    }

    /**
     * 根据URL分发到Controller和Model
     * @access      public
     * @param       array   $url_array
     */
    public static function routeToCm($url_array = array()) {
        $app = '';
        $controller = '';
        $action = '';
        $model = '';
        $params = '';
        self::$_urlparams = $url_array;
		//带有文件夹的model和controller，比如后台admin等
        if (isset($url_array['app'])) {
            $app = $url_array['app'];
        }
        $controller = self::$_config['route']['default_controller']; //controller 只有一个
        if (isset($url_array['r'])) {
            $model = isset($url_array['r']) ? $url_array['r'] : self::$_config['route']['default_controller'];
            if ($app) {
                $controller_file = CONTROLLER_PATH . '/' . $app . '/' . $controller . 'Controller.php';
                $model_file = MODEL_PATH . '/' . $app . '/' . $model . 'Model.php';
            } else {
                $controller_file = CONTROLLER_PATH . '/' . $controller . 'Controller.php';
                $model_file = MODEL_PATH . '/' . $model . 'Model.php';
            }
        } else {
            if ($app) {
                $controller_file = CONTROLLER_PATH . '/' . $app . '/' . self::$_config['route']['default_controller'] . 'Controller.php';
                $model_file = MODEL_PATH . '/' . $app . '/' . self::$_config['route']['default_controller'] . 'Model.php';
            } else {
                $controller_file = CONTROLLER_PATH . '/' . self::$_config['route']['default_controller'] . 'Controller.php';
                $model_file = MODEL_PATH . '/' . self::$_config['route']['default_controller'] . 'Model.php';
            }
        }

        /**
         * 获取spider配置，并替换默认配置
         * 如果存在则替换，如果不存在则终止程序的执行
         */
        $spider_file = Spider_Conf_PATH . '/' . $model . 'Spider.php';
        if (file_exists($spider_file)) {
            require $spider_file;
            //若存在特定的数据库配置则更新，否则使用默认的数据库配置
            if (isset($siteconfig['db'])) {
                if (isset($siteconfig['db']['mongodb'])) {
                    self::$_config['db']['mongodb'] = $siteconfig['db']['mongodb'];
                }
                if(isset ($siteconfig['db']['redis'])){
                    self::$_config['db']['redis'] = $siteconfig['db']['redis'];
                }
                if(isset ($siteconfig['db']['mysql'])){
                    self::$_config['db']['mysql'] = $siteconfig['db']['mysql'];
                }
            }
            self::$_spider = $siteconfig;
            self::$_spidername = $model;
            //传入进程数
            if(isset($url_array['process']) && $url_array['process']>0 && $url_array['a'] == 'fulldata')
            	self::$_process = $url_array['process'];
            else if(isset($url_array['process']) && $url_array['process']>0 && $url_array['a'] == 'itemmaster'){
            	self::$_process = $url_array['process'];
            }else if(isset($url_array['process']) && $url_array['process']>0 && $url_array['a'] == 'updatemaster'){
            	self::$_process = $url_array['process'];
            }
        } else {
            echo $model . "'s Spider doesn't exist.";
            exit;
        }
        
        /**
         * 加载后处理模块
         */
        $product_file = MODEL_PATH . '/' . $model . 'ProductModel.php';
        if (file_exists($product_file)) {
        	require $product_file;
        } else {
        	echo $model . "'s ProductModel doesn't exist.";
        	exit;
        }
       
        if (isset($url_array['a'])) {
            $action = $url_array['a'];
        } else {
            $action = self::$_config['route']['default_action'];
        }

        /**
         * 判断扩展的model是否存在，如果存在则调用，否则视为默认的
         */
        if (isset($url_array['params'])) {
            $params = $url_array['params'];
        }
        if (file_exists($controller_file)) {
            if (file_exists($model_file)) {
                require $model_file;
                self::$_spidermodel = $model;
            }
            require $controller_file;
            $controller = $controller . 'Controller';
            $controller = new $controller;
            /**
             * 控制器、方法校验
             */
            if ($action && method_exists($controller, $action)) {
                isset($params) ? $controller->$action($params) : $controller->$action();
            } else {
                echo ('控制器方法不存在');
                //  die('控制器方法不存在');
            }
        } else {
            die('默认控制器不存在');
        }
    }

}
