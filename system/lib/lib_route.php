<?php
/**
 * URL处理类
 * @copyright   Copyright(c) 2012
 * @author      cnluckylee <cnluckylee@gmail.com>
 * @version     1.0
 */
final class Route{
        public $url_query;
        public $url_type;
        public $route_url = array();


        public function __construct() {
                $this->url_query = isset($_SERVER['REQUEST_URI'])?parse_url($_SERVER['REQUEST_URI']):"";
        }
        /**
         * 设置URL类型
         * @access      public
         */
        public function setUrlType($url_type = 2){
                if($url_type > 0 && $url_type <4){
                        $this->url_type = $url_type;
                }else{
                        trigger_error("指定的URL模式不存在！");
                }
        }

        /**
         * 获取数组形式的URL
         * @access      public
         */
        public function getUrlArray(){
                $this->makeUrl();
                return $this->route_url;
        }
        /**
         * @access      public
         */
        public function makeUrl(){
                switch ($this->url_type){
                        case 1:
                                $this->querytToArray();
                                break;
                        case 2:
                                $this->pathinfoToArray();
                                break;
                        case 3:
                        		$this->ConsoleToArray();
                }
        }
        /**
         * 将query形式的URL转化成数组
         * @access      public
         */
        public function querytToArray(){
                $arr = !empty ($this->url_query['query']) ?explode('&', $this->url_query['query']) :array();
                $array = $tmp = array();
                if (count($arr) > 0) {
                        foreach ($arr as $item) {
                                $tmp = explode('=', $item);
                                $array[$tmp[0]] = $tmp[1];
                        }
                        if (isset($array['app'])) {
                                $this->route_url['app'] = $array['app'];
                                unset($array['app']);
                        }
                        if (isset($array['r'])) {
                                $this->route_url['r'] = $array['r'];
                                unset($array['r']);
                        }
                        if (isset($array['a'])) {
                                $this->route_url['a'] = $array['a'];
                                unset($array['a']);
                        }
                        if(count($array) > 0){
                                $this->route_url['params'] = $array;
                        }
                }else{
                        $this->route_url = array();
                }
        }
        /**
         * 将PATH_INFO的URL形式转化为数组
         * @access      public
         */
        public function pathinfoToArray(){

        }
        /**
         * 将命令行转成数组
         */
        public function ConsoleToArray()
        {
        	if(!isset($_SERVER[ 'argv' ]))
        		$this->querytToArray();
        	else{
	        	$argv = $_SERVER[ 'argv' ];  
	        	$this->route_url['r'] = $argv[1];
	        	$this->route_url['a'] = $argv[2];
	        	if(isset($argv[3]))
	        	{
	        		$this->route_url['process'] = $argv[3];
	        	}
	        	if(count($argv) > 4){
	        		$this->route_url['params'] = array_slice($argv, 3);
	        	}
	        	unset($argv);
	        	return $this->route_url;
        	}
        }
}


