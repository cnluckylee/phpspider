<?php
/**
 * 核心控制器
 * @copyright   Copyright(c) 2012
 * @author      cnluckylee <cnluckylee@gmail.com>
 * @version     1.0
 */
class Controller{

        public function __construct() {
               // header('Content-type:text/html;chartset=utf-8');
        }
        /**
         * 实例化模型
         * @access      final   protected
         * @param       string  $model  模型名称
         */
        final protected function model($model) {
                if (empty($model)) {
                        trigger_error('不能实例化空模型');
                }
                $model_name = $model . 'Model';
                return new $model_name;
        }
        /**
         * 加载类库
         * @param string $lib   类库名称
         * @param Bool  $my     如果FALSE默认加载系统自动加载的类库，如果为TRUE则加载非自动加载类库
         * @return type
         */
        final protected function load($lib,$auto = TRUE){
                if(empty($lib)){
                        trigger_error('加载类库名不能为空');
                }elseif($auto === TRUE){
                        return Application::$_lib[$lib];
                }elseif($auto === FALSE){
                        return  Application::newLib($lib);
                }
        }
        /**
         * 加载系统配置,默认为系统配置 $CONFIG['system'][$config]
         * @access      final   protected
         * @param       string  $config 配置名
         */
        final   protected function config($config){
                return Application::$_config[$config];
        }
}


