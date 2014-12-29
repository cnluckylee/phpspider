<?php

/* mongodb配置 */
$CONFIG['system']['db'] = array(
    'mongodb' => array(
        'host' => 'mongo.wcc.cc',
        'port' => 3376,
        'timeout' => 0,
        'dbname' => 'wcc_online_data'
    ),
    'redis' => array(
        'host' => 'redis.wcc.cc',
        'port' => 3362,
        'timeout' => 0
    ),
/*		
 * mysql 在各自spider中配置
    'mysql' => array(
        'host' => 'mysql.wcc.cc',
        'port' => 3306,
        'username' => 'ross',
        'password' => '',
        'dbname' => 'wcc_online_data'
    )
*/    
);


/* 自定义类库配置 */
$CONFIG['system']['lib'] = array(
    'prefix' => 'my'
);

$CONFIG['system']['route'] = array(
    'default_controller' => 'spider', //默认系统控制器
    'default_action' => 'index', //系统默认控制器
	'default_model' => 'spider',//默认model
    'url_type' => 3
        /**
         * URL类型：1  普通形式   index.php?c=controller&a=action&id=2
         *         2  PATHINFO  index.php/controller/action/id/2 (待定)
         *         3  命令行方式  php index.php controllername  actionname pram1 pram2...
         */
);

/* 缓存配置 */
$CONFIG['system']['cache'] = array(
    'cache_dir' => 'cache', //缓存目录，相对于根目录
    'cache_prefix' => 'cache_', //缓存文件名前缀
    'cache_time' => 1800, //缓存时间默认为1800秒
    'cache_mode' => 2, //mode 1为seralize , mode 2为保存为可执行文件
);
