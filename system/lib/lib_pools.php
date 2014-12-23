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
        $this->addjob($k,$v);
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

            $value = $this->redis->spop($key);
            $lists[$value] = $value;
            return $lists;

		}
	}

    /**
     * 新增任务完成监控
     * 加入任务，重复的则增加单个任务重复的次数
     */
    public function addjob($spidername,$jobname,$jobnum=1)
    {
        $spidername .='Bak';
        //判断是否已经添加进去了
        $f = $this->redis->hexists($spidername,$jobname);
        if($f)
            $this->redis->hincrby($spidername,$jobname,$jobnum);
        else
            $this->redis->hset($spidername,$jobname,$jobnum);
    }

    /**
     * 新增任务完成监控
     * 加入任务，重复的则增加单个任务重复的次数
     */
    public function deljob($spidername,$jobname)
    {
        $spidername .='Bak';
        //判断是否已经添加进去了
        $this->redis->hdel($spidername,$jobname);
    }
}