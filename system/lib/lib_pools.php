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
		$this->redis->sadd($k,$v);
	}
	public function del($k)
	{
		$this->redis->delete($k);
	}
	public function size($k)
	{
		return $this->redis->scard($k);
	}
	public function get($key,$num=null)
	{
		$lists = array();
		if($num>1)
		{
			for($i=0;$i<$num;$i++)
			{
				$value = $this->redis->spop($key);
				$lists[$value] = $value;
			}
			return $lists;
		}else{
			if($num==null)
				return $this->redis->spop($key);
			else{
				$value = $this->redis->spop($key);
				$lists[$value] = $value;
				return $lists;
			}
		}
	}
}