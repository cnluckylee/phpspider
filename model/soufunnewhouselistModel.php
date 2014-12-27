<?php 

class soufunnewhouselistModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'soufunnewhouse_area';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $data = $this->mongodb->find($collection,array());
        $result = array();

        /**
         * 写入mongodb category集合
         */
        $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
        foreach($data as $k=>$v)
        {
            $name = $v['name'];
            $cid = $url = $v['cid'].'c6y-b9';
            $this->pools->set ( $poolname, $url );
            $mondata2 = array (
                'name' => $name,
                'cid' => $cid,
                'sid' => $sid
            );

            $this->mongodb->insert ( $collection_category_name, $mondata2 );
        }
        unset($result);
        echo "do over"."\n";
        exit;
    }

    function getcategorytotalpages($Categoryurl,$job,$jobname,$Category) {
        // 首先获取下该分类下面的总页数
        $pageHtml = $this->curlmulit->remote ( $Categoryurl,null,false,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);
        if (! $pageHtml) {
//			$this->autostartitemmaster ();

            $this->log->errlog ( array (
                'job' => $job,
                'Categoryurl' => $Categoryurl,
                'error' => 2,
                'addtime' => date ( 'Y-m-d H:i:s' )
            ) );
            return 0;
        }


        $preg_pagetotals = $Category [elements::CATEGORY_LIST_PREG];

        preg_match ( $preg_pagetotals, $pageHtml [$Categoryurl], $match_pagetotals );
        foreach($match_pagetotals as $k=>$v)
        {
            $match_pagetotals[$k] = trim($v);
        }
        $totalpages = $match_pagetotals ? $match_pagetotals [$Category [elements::CATEGORY_LIST_MATCH]] : 0;


        if(!$totalpages && $pageHtml){
            $this->log->errlog ( array (
                'job' => $job,
                'Categoryurl' => $Categoryurl,
                'error' => 2,
                'yy' =>'no total and have page',
                'addtime' => date ( 'Y-m-d H:i:s' )
            ) );
        }
        return $totalpages;
    }
}