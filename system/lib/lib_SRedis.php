<?php
/**
 * Redis database connection class
 * 
 * @modify Michael
 */
 
 //	class RedisException
class RedisException extends Exception {}
//	class Redis
class SRedis {
	private $port;
	private $host;
	private $_sock;
	private $pipeline = false;
	private $pipeline_commands = 0;
	public $debug=false;
	
	public function __construct(){
		$redisconfig =  Application::$_config['db']['redis'];
		$this->host = $redisconfig['host'];
		$this->port = $redisconfig['port'];
	}

//	byfsockopen
	private function connect() {
		if ($this->_sock)
			return;
		if ($sock = fsockopen ( $this->host, $this->port, $errno, $errstr )) {
			$this->_sock = $sock;
			$this->debug('Connected');
			return;
		}
		$msg = "Cannot open socket to {$this->host}:{$this->port}";
		if ($errno || $errmsg)
			$msg .= "," . ($errno ? " error $errno" : "") . ($errmsg ? " $errmsg" : "");
		throw new RedisException ( "$msg." );
	}

//debug
	private function debug($msg){
		if ($this->debug) echo sprintf("[Redis] %s\n", $msg);
	}

//read	
	private function read() {
		if ($s = fgets ( $this->_sock )) {
			$this->debug('Read: '.$s.' ('.strlen($s).' bytes)');
			return $s;
		}
		$this->disconnect ();
		throw new RedisException ( "Cannot read from socket." );
	}
//cmdResponse
	private function cmdResponse() {
		// Read the response
		$s = trim ( $this->read () );
		switch ($s [0]) {
			case '-' : // Error message
				throw new RedisException ( substr ( $s, 1 ) );
				break;
			case '+' : // Single line response
				return substr ( $s, 1 );
			case ':' : //Integer number
				return substr ( $s, 1 ) + 0;
			case '$' : //Bulk data response
				$i = ( int ) (substr ( $s, 1 ));
				if ($i == - 1)
					return null;
				$buffer = '';
				if ($i == 0){
					$s = $this->read ();
				}
				while ( $i > 0 ) {
					$s = $this->read ();
					$l = strlen ( $s );
					$i -= $l;
					if ($i < 0)
						$s = substr ( $s, 0, $i );
					$buffer .= $s;
				}
				return $buffer;
				break;
			case '*' : // Multi-bulk data (a list of values)
				$i = ( int ) (substr ( $s, 1 ));
				if ($i == - 1)
					return null;
				$res = array ();
				for($c = 0; $c < $i; $c ++) {
					$res [] = $this->cmdResponse ();
				}
				return $res;
				break;
			default :
				throw new RedisException ( 'Unknown responce line: ' . $s );
				break;
		}
	}

	public function pipeline_begin(){
		$this->pipeline = true;
		$this->pipeline_commands = 0;
	}
	public function pipeline_responses(){
		$response = array();
		for ($i=0;$i<$this->pipeline_commands;$i++){
			$response[] = $this->cmdResponse();
		}
		$this->pipeline = false;
		return $response;
	}
	
	private function cmd($command) {
		$this->debug('Command: '.(is_array($command)?join(', ',$command):$command));
		$this->connect ();
		
		if (is_array($command)){
			// Use unified command format
			
			$s = '*'.count($command)."\r\n";
			foreach ($command as $m){
				$s.='$'.strlen($m)."\r\n";
				$s.=$m."\r\n";
			}
		}
		else{
			$s = $command . "\r\n";
		}
		while ( $s ) {
			$i = fwrite ( $this->_sock, $s );
			if ($i == 0)
				break;
			$s = substr ( $s, $i );
		}
		if ($this->pipeline){
			$this->pipeline_commands++;
			return null;
		}
		else{
			return $this->cmdResponse ();
		}
	}
	public function disconnect() {
		if ($this->_sock)
			@fclose ( $this->_sock );
		$this->_sock = null;
	}
	
//quit
	public function quit() {
		return $this->cmd ( 'QUIT' );
	}
	

	public function auth($password) {
		return $this->cmd ( array('AUTH',$password) );
	}
	

	public function set($key, $value, $preserve = false) {
		return $this->cmd ( array( ($preserve ? 'SETNX' : 'SET') , $key, $value) );
	}

	public function get($key) {
		$args = func_get_args();
		if (count($args) > 1){
			$key = $args;
		}
		if (is_array($key)){
			array_unshift($key, "MGET");
			return $this->cmd ( $key );
		}
		else{
			return $this->cmd ( array("GET", $key));
		}
	}
	function __get($key) {
		return $this->get ( $key );
	}
	function __set($key, $value) {
		return $this->set ( $key, $value );
	}

	public function getset($key, $value) {
		return $this->cmd ( array("GETSET", $key, $value) );
	}

	public function incr($key, $amount = 1) {
		if ($amount == 1)
			return $this->cmd ( array("INCR", $key) );
		else
			return $this->cmd ( array("INCRBY",$key,$amount) );
	}

	public function decr($key, $amount = 1) {
		if ($amount == 1)
			return $this->cmd ( array("DECR", $key) );
		else
			return $this->cmd ( array("DECRBY", $key, $amount) );
	}

	public function exists($key) {
		return $this->cmd ( array("EXISTS", $key) );
	}
	function __isset($key){
		return $this->exists($key);
	}
	
	public function delete($key) {
		return $this->cmd ( array("DEL", $key) );
	}
	
	function __unset($key){
		return $this->delete($key);
	}

	public function type($key){
		return $this->cmd ( array("TYPE", $key) );
	}
	
	public function keys($pattern) {
		return $this->cmd ( array("KEYS", $pattern) );
	}

	public function randomkey() {
		return $this->cmd ( "RANDOMKEY" );
	}
	
	public function rename($src, $dst, $preserve = False) {
		if ($preserve) {
			return $this->cmd ( array("RENAMENX", $src, $dst) );
		}
		return $this->cmd ( array("RENAME", $src, $dst) );
	}

	public function dbsize(){
		return $this->cmd ("DBSIZE");
	}

	public function expire($key, $ttl){
		return $this->cmd (array("EXPIRE", $key, $ttl));
	}

	public function ttl($key){
		return $this->cmd (array("TTL", $key));
	}
	
	public function push($key, $value, $tail = true) {
		// default is to append the element to the list
		return $this->cmd ( array($tail ? 'RPUSH' : 'LPUSH',  $key, $value) );
	}

	public function llen($key) {
		return $this->cmd ( array("LLEN", $key) );
	}

	public function lrange($key, $start, $end) {
		return $this->cmd ( array("LRANGE", $key, $start, $end) );
	}
	
	public function ltrim($key, $start, $end) {
		return $this->cmd ( array("LTRIM", $key, $start, $end) );
	}

	public function lindex($key, $index) {
		return $this->cmd ( array("LINDEX", $key, $index) );
	}
	
	public function lset($key, $value, $index) {
		return $this->cmd ( array("LSET", $key, $index, $value) );
	}

	public function lrem($key, $value, $count=1) {
		return $this->cmd ( array("LREM", $key, $count, $value) );
	}
	
	public function pop($key, $tail = true) {
		return $this->cmd ( array($tail ? 'RPOP' : 'LPOP', $key) );
	}
	
	public function sadd($key, $value) {
		return $this->cmd ( array("SADD", $key, $value) );
	}

	public function srem($key, $value) {
		return $this->cmd ( array("SREM", $key, $value) );
	}

	public function spop($key){
		return $this->cmd( array("SPOP", $key) );
	}

	public function smove($srckey, $dstkey, $member){
		$this->cmd(array("SMOVE", $srckey, $dstkey, $member));
	}

	public function scard($key) {
		return $this->cmd ( array("SCARD", $key) );
	}
	
	public function sismember($key, $value) {
		return $this->cmd ( array("SISMEMBER", $key, $value) );
	}

	public function sinter($key1) {
		if (is_array($key1)){
			$sets = $key1;
		}
		else{
			$sets = func_get_args();
		}
		array_unshift($sets, 'SINTER');
		return $this->cmd ( $sets );
	}

	public function sinterstore($dstkey, $key1) {
		if (is_array($key1)){
			$sets = $key1;
			array_unshift($sets, $dstkey);
		}
		else{
			$sets = func_get_args();
		}
		array_unshift($sets, 'SINTERSTORE');
		return $this->cmd ( $sets );
	}

	public function sunion($key1) {
		if (is_array($key1)){
			$sets = $key1;
		}
		else{
			$sets = func_get_args();
		}
		array_unshift($sets, 'SUNION');
		return $this->cmd ( $sets );
	}

	public function sunionstore($dstkey, $key1) {
		if (is_array($key1)){
			$sets = $key1;
			array_unshift($sets, $dstkey);
		}
		else{
			$sets = func_get_args();
		}
		array_unshift($sets, 'SUNIONSTORE');
		return $this->cmd ( $sets );
	}

	public function sdiff($key1) {
		if (is_array($key1)){
			$sets = $key1;
		}
		else{
			$sets = func_get_args();
		}
		array_unshift($sets, 'SDIFF');
		return $this->cmd ( $sets );
	}

	public function sdiffstore($dstkey, $key1) {
		if (is_array($key1)){
			$sets = $key1;
			array_unshift($sets, $dstkey);
		}
		else{
			$sets = func_get_args();
		}
		array_unshift($sets, 'SDIFFSTORE');
		return $this->cmd ( $sets );
	}

	public function smembers($key) {
		return $this->cmd ( array("SMEMBERS", $key) );
	}
	
	
	public function select_db($key) {
		return $this->cmd ( array("SELECT", $key) );
	}

	public function move($key, $db) {
		return $this->cmd ( array("MOVE", $key, $db) );
	}

	public function flushdb(){
		return $this->cmd ( "FLUSHDB" );
	}

	public function flushall(){
		return $this->cmd ( "FLUSHALL" );
	}

	public function sort($key, $query = false) {
		if ($query === false) {
			return $this->cmd ( array("SORT", $key) );
		} else {
			return $this->cmd ( array("SORT", $key, $query) );
		}
	}

	public function save($background = false) {
		return $this->cmd ( ($background ? "BGSAVE" : "SAVE") );
	}

	public function lastsave() {
		return $this->cmd ( "LASTSAVE" );
	}

	public function shutdown(){
		return $this->cmd ( "SHUTDOWN" );
	}
	
	public function info($section = false){
		if ($section === false) {
			return $this->cmd ( "INFO" );
		} else {
			return $this->cmd ( array("INFO", $section) );
		}
	}

	public function slaveof($host=null, $port=6379){
		return $this->cmd(array('SLAVEOF', $host?"$host $port":'no one'));
	}
	
	public function ping() {
		return $this->cmd ( "PING" );
	}
	public function do_echo($s) {
		return $this->cmd ( array("ECHO", $s) );
	}
	
	function __call($name, $params){
		array_unshift($params, strtoupper($name));
		return $this->cmd($params);
	}

    /**
     * @param $key
     * @param array $fields likes array(field1,field2,...)
     * @return array|null|string
     */
    public function hmget($key, array $fields) {
		$getcmd = array('HMGET',$key);
		return $this->cmd ( array_merge($getcmd, $fields));
	}

    /**
     * @param $key
     * @param array $fieldsAndValues likes array(field1,value1,field2,value2,...)
     * @return array|null|string
     */
    public function hmset($key, array $fieldsAndValues) {
		$setcmd = array('HMSET',$key);
		return $this->cmd ( array_merge($setcmd, $fieldsAndValues));
	}

    /**
     * @param $key
     * @param array $fields likes array(field1,field2,...)
     * @return array|null|string
     */
    public function hdel($key, array $fields){
		$delcmd = array('HDEL',$key);
		return $this->cmd ( array_merge($delcmd, $fields));
	}

    public function hexists($key, $field){
        return $this->cmd(array('HEXISTS',$key,$field));
    }

    public function hget($key,$field){
        return $this->cmd(array('HGET',$key,$field));
    }

    public function hset($key,$field,$value){
        return $this->cmd(array('HSET',$key,$field,$value));
    }

    public function hsetnx($key,$field,$value){
        return $this->cmd(array('HSETNX',$key,$field,$value));
    }

    public function hgetall($key){
        $fieldAndValue = $this->cmd(array('HGETALL',$key));
        $len = count($fieldAndValue);
        $res = array();
        for($i=0;$i<$len;$i+=2){
            $res[$fieldAndValue[$i]] = $fieldAndValue[$i+1];
        }
        return $res;
    }

    public function hkeys($key){
        return $this->cmd(array('HKEYS',$key));
    }

    public function hlen($key){
        return $this->cmd(array('HLEN',$key));
    }

    public function hvals($key){
        return $this->cmd(array('HVALS',$key));
    }
}