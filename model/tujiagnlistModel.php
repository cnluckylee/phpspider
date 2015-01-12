<?php 

class tujiagnlistModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'tujia_area';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $result = array();
        $mondata = $this->mongodb->find($collection,array());
        foreach($mondata as $item)
        {
            $url = $item['cid'];
            $result[] = $url;
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
        if($mondata)
            $this->mongodb->batchinsert ( $collection_category_name, $mondata2 );
        unset($mondata);
    }

    function CategroyJob() {
        header("Content-type: text/html; charset=utf-8");
        $name = $this->spidername . 'Category';
        $jobname = 'Category';
        $spidername = str_replace ( 'Spider', "", $this->spidername );
        $tmp = $this->pools->get ( $name );

        $jobs = array_values($tmp);
        $job = $jobs[0];


        $poolname = $this->spidername . 'Item';
        $Category = Application::$_spider [elements::CATEGORY];
        $xpath = $Category [elements::CATEGORY_MATCHING];
        if(isset($Category [elements::TRANSFORM]) && $Category [elements::TRANSFORM] === false)
            $Categoryurl = $job.$Category [elements::TRANSFORMADDSPECIL];
        else{
            $Categoryurl = str_replace ( "#job", $job, $Category [elements::CATEGORY_LIST_URL] );
            $Categoryurl .= $Category [elements::TRANSFORMADDSPECIL];
        }
            $Categoryurl = str_replace ( "#job", $job, $Category [elements::CATEGORY_LIST_URL] );

        // 首先获取下该分类下面的总页数
        $pageHtml = $this->curlmulit->remote ( $Categoryurl,null,false,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);
        if (! $pageHtml) {
//			$this->autostartitemmaster ();
            $this->redis->decr ( $this->spidername . 'CategoryTotalCurrent' );
            $this->redis->hincrby ( $this->spidername . $jobname . 'Current',HOSTNAME,-1);
            $this->log->errlog ( array (
                'job' => $job,
                'Categoryurl' => $Categoryurl,
                'error' => 2,
                'addtime' => date ( 'Y-m-d H:i:s' )
            ) );
            exit ();
        }
        $page = $pageHtml[$Categoryurl];
        $pagearr = json_decode($page,true);
        $totalpages = ceil($pagearr['totalCount']/30);


        //获取分类列表页总页数，如果获取不到则自动停止，并做好相应记录


        $s = isset ( $Category [elements::CATEGORY_PAGE_START] ) ? $Category[elements::CATEGORY_PAGE_START] : 0;
        $pagesize = $Category [elements::CATEGORY_GROUP_SIZE];
        if ($totalpages > 0) {
            $totalpages +=1;
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
                    if(isset($Category [elements::TRANSFORM]) && $Category [elements::TRANSFORM] == false)
                    {
                        if(isset($Category [elements::CATEGORY_NO_ADD_PAGE]) && $Category [elements::CATEGORY_NO_ADD_PAGE])
                            $url =$job.$Category [elements::TRANSFORMADDSPECIL];
                        else
                            $url =$job.$i.$Category [elements::TRANSFORMADDSPECIL];
                    }else{
                        $url = $Category [elements::CATEGORY_LIST_PAGES_URL];
                        $url = str_replace ( '#job', $job, $url );
                        $url = str_replace ( '#i', $i, $url );
                        $url .= time().rand(100,999);
                    }
                    $tmpurls [$url] = $url;
                }
                $pages = $this->curlmulit->remote ( $tmpurls, null, false ,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);

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

                    if(strtolower($Category[elements::CATEGORY_ITEM_PREG][elements::CATEGORY_ITEM_MATCHING]) == 'xpath')
                    {
                        $item_urls = $this->curlmulit->getRegexpInfo2($preg,$page);
                    }else{
                        preg_match_all ( $preg, $page, $match_out );
                        $item_urls = isset ( $match_out [$match] ) ? $match_out [$match] : "";
                    }
                    $item_urls = array_unique ( $item_urls );
                    if(!$page)
                        print_r($tmpurls);
                    //加入错误日志
                    unset($tmpurls[$rurl]);
                    //加入列表页数据的获取并保存
                    if(isset($Category [elements::CATEGORY_ITEM_PREG]))
                    {
                        $Productmodel = $this->spidername . 'ProductModel';
                        $tmp = json_decode($page,true);
                        $page = $tmp['unitListHtml'];
                        $page = '<html><body>'.$page.'</body></html>';

                        $spidermodel = new $Productmodel ( $this->spidername, $rurl, $page, $Category [elements::CATEGORY_ITEM_PREG] );
                        $categorydata = $spidermodel->CategoryToArray ( );
//print_r($categorydata);
                        if($categorydata){
                            foreach($categorydata as $item)
                            {
                                $item['Category_Source_Url'] = $rurl;
                                $item['job'] = $job;
                                if($item[\elements::CATEGORY_ITEM_URL])
                                    $this->pools->set ( $poolname, $item[\elements::CATEGORY_ITEM_URL] );//将category_item_url加入任务池中 2014.12.20 22:32
                                $this->mongodb->insert($this->spidername.'_category_list',$item);
                            }
                        }else{
                            $s = $s+ $totalpages;

                        }

                    }
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
//		$this->autostartitemmaster ();
        exit ();
    }
}