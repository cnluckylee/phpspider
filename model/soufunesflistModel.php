<?php

class soufunesflistModel extends spiderModel
{
    /*
    public function  getCategory()
    {
        $collection = 'soufunbroker_category_list';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $result = array();
        $total = $this->mongodb->count($collection);
        $s = 0;
        $limit = 1000;
        if($total>0)
            $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
        do {
            $mondata2 = $result = array ();
            $mondata = $this->mongodb->find ( $collection, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            foreach($mondata as $item)
            {
                $sourceurl = $item['Category_Item_Url'];
                $arr = parse_url($sourceurl);
                $baseurl = $arr['scheme']."://".$arr['host'];
                $result[] = $baseurl.'/agent/agentnew/AloneesfHList.aspx?&agentid='.$item['Category_Item_Name'].'&pricemax=&page=';
            }
            $Categorylist = array_unique ( $result );

            foreach ( $Categorylist as $name => $cid ) {
                $this->pools->set ( $poolname, $cid );
                $mondata2 [] = array (
                    'name' => $name,
                    'cid' => $cid,
                    'sid' => $sid
                );
            }
            $s +=$limit;
            if($mondata)
                $this->mongodb->batchinsert ( $collection_category_name, $mondata2 );
            unset($mondata);

        }while($s<$total);
        exit;
    }
*/

    function CategroyJob() {
        header("Content-type: text/html; charset=utf-8");
        $name = $this->spidername . 'Category';
        $jobname = 'Category';
        $spidername = str_replace ( 'Spider', "", $this->spidername );
        $Category = Application::$_spider [elements::CATEGORY];
        $tmp = $this->pools->get ( $name );
        $jobs = array_values($tmp);
        $job = $jobs[0];
        $sourceurl = $Categoryurl = $job.'newsecond/Map/Interfaces/getHouseData.aspx?businesstype=&y2=&v=2014.12.18.20&pagesize=100&page=';
        $poolname = $this->spidername . 'Item';
        $pageHtml = $this->curlmulit->remote ( $Categoryurl,null,false,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);
        if (! $pageHtml) {
//			$this->autostartitemmaster ();
            $this->redis->decr ( $this->spidername . 'CategoryTotalCurrent' );
            $this->redis->hincrby ( $this->spidername . $jobname . 'Current',HOSTNAME,-1);
            $this->log->errlog ( array (
                'job' => $job,
                'Categoryurl' => $Categoryurl,
                'yy'=>"not find page",
                'error' => 2,
                'addtime' => date ( 'Y-m-d H:i:s' )
            ) );
            exit ();
        }
        $jsondata = $pageHtml[$Categoryurl];
        $data = json_decode($jsondata,true);
        $itemtotals = $data['allcount'];
        // 首先获取下该分类下面的总页数
        $totalpages = ceil($itemtotals/100);
        $totalpages = $totalpages>100?100:$totalpages;//最多只监听100页数据
        if(!$totalpages && $pageHtml){
            $this->log->errlog ( array (
                'job' => $job,
                'Categoryurl' => $Categoryurl,
                'error' => 2,
                'yy' =>'no total and have page',
                'addtime' => date ( 'Y-m-d H:i:s' )
            ) );
        }
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        if($totalpages && $totalpages>0){
            $this->mongodb->update ( $collection_category_name,
                array ('cid' => $job),
                array('$set'=>array('totalcount'=>$totalpages)),
                array("upsert"=>1,"multiple"=>true));
        }
        $s = isset ( $Category [elements::CATEGORY_PAGE_START] ) ? $Category[elements::CATEGORY_PAGE_START] : 0;
        $pagesize = $Category [elements::CATEGORY_GROUP_SIZE];
        if ($totalpages > 0) {
            $randtimes = ceil ( $totalpages / $pagesize );
            // 循环获取商品的url地址
            do {
                if ($totalpages < $pagesize) {
                    $e = $totalpages;
                } else {
                    $e = $s + $pagesize;
                }
                $tmpurls = array ();
                for($i = $s; $i < $e; $i ++) {
                    $url =$sourceurl.$i;
                    $tmpurls [$url] = $url;
                }
                $s = $s + $pagesize;
                $pages = $this->curlmulit->remote ( $tmpurls, $job, false ,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);
                /**
                 * 能否抓去到数据检测,此代码保留
                 */
                if ($s == 0 && count ( $pages ) == 0) {
                    $this->master ( 'Item' );
                    $this->redis->decr ( $this->spidername . 'CategoryTotalCurrent' );
                    $this->redis->hincrby ( $this->spidername . $jobname . 'Current',HOSTNAME,-1);
                    $this->log->errlog ( array (
                        'job' => $job,
                        'Categoryurl' => $Categoryurl,
                        'error' => 1,
                        'addtime' => date ( 'Y-m-d H:i:s' )
                    ) );
                    exit ();
                }
                $preg = $Category [elements::CATEGORY_LIST_GOODS_PREG];
                $match = $Category [elements::CATEGORY_LIST_GOODS_Match];
                $baseurl = '';
                foreach ( $pages as $rurl => $page ) {
                        $jdata = json_decode($page,true);
                        $categorydata = $jdata['house'];
                        if($categorydata){
                            foreach($categorydata as $item)
                            {
                                $item[\elements::CATEGORY_ITEM_URL] = $job.$item['houseurl'];
                                $item[\elements::CATEGORY_ITEM_SKUID] = $item['houseid'];
                                $item['registtime'] = $this->tools->getSourceUpdateTime($item['registdate']);
                                $item['create_time'] = date('Y-m-d H:i:s');
                                $item['job'] = $rurl;
                                if($item[\elements::CATEGORY_ITEM_URL])
                                    $this->pools->set ( $poolname, $item[\elements::CATEGORY_ITEM_URL] );//将category_item_url加入任务池中 2014.12.20 22:32
                                $this->mongodb->insert($this->spidername.'_category_list',$item);
                            }
                        }
                    //加入错误日志
                    unset($tmpurls[$rurl]);
                }

                $s = $s + $pagesize;
                if($tmpurls)
                {
                    foreach($tmpurls as $url)
                        $this->log->errlog ( array (
                            'job' => $job,
                            'url' => $url,
                            'urltype' =>'CategoryList',
                            'error' => 1,
                            'addtime' => date ( 'Y-m-d H:i:s' )
                        ) );
                }
                $sleep = rand(1,3);
                sleep($sleep);
            } while ( $s <= $totalpages );
        }
        $this->pools->deljob($name,$job);//加入删除备份任务机制
        $this->redis->decr ( $this->spidername . 'CategoryTotalCurrent' );
        $this->redis->hincrby ( $this->spidername . $jobname . 'Current',HOSTNAME,-1);
        exit ();
    }
}