<?php
/**
 * 数据池
 */
class pools{
	/**
	 * 
	 */
	protected $redis;
	public function init($redis)
	{
		$this->redis = $redis;
	}
	public function set($k,$v)
	{
		$this->redis->push($k,$v);
	}
}