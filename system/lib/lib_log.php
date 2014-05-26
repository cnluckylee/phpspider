<?php
class log{
	protected $mongodb;
	protected $spidername;
	public function init($mongodb,$spidername)
	{
		$this->mongodb = $mongodb;
		$this->spidername = $spidername;
	}
	public function runlog($logs)
	{
		$this->mongodb->insert($this->spidername.'_run_log',$logs);
	}
	public function errlog($logs)
	{
		/**
		 * error 1:抓取不到分类列表页面 2：抓取不到分类列表页总数
		 */
		$this->mongodb->insert($this->spidername.'_err_log',$logs);
	}
	public function warninglog($logs)
	{
		$this->mongodb->insert($this->spidername.'_warning_log',$logs);
	}
	public function msglog($logs)
	{
		$this->mongodb->insert($this->spidername.'_msg_log',$logs);
	}
}