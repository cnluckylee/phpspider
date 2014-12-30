<?php

class lejuesflist2Model extends spiderModel
{
    function getCategory() {
        header("Content-type: text/html; charset=utf-8");
        $Category = Application::$_spider ['Category'];
        $thistimerun = isset ( $Category ['Category_Run'] ) ? $Category ['Category_Run'] : 1;
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        // 清理Category现场
        $this->pools->del ( $poolname );
        $this->redis->delete ( $this->spidername . 'CategoryCurrent' );
        $this->redis->delete ( $this->spidername . 'ItemCurrent' );
        $this->redis->delete ( $this->spidername . 'Item' );
        $this->redis->delete ( $this->spidername . 'ItemJobRun' );
        // 判断本次是否重新抓取分类数据
        if ($thistimerun) {
            $Category_URL = $Category[elements::CATEGORY_URL];
            $tmp = $this->curlmulit->remote( $Category_URL , null, false, Application::$_spider[elements::CHARSET],Application::$_spider [ elements::ITEMPAGECHARSET]);
            $page = $tmp[$Category_URL];
            $Categorytmp = array();
            $preg = '//div[@class="city_list"]/a/@href';
            $tmphref = $this->curlmulit->getRegexpInfo2($preg,$page);
            $preg = '//div[@class="city_list"]/a/text()';
            $tmptext = $this->curlmulit->getRegexpInfo2($preg,$page);
            foreach($tmptext as $k=>$v)
            {
                $Categorytmp[$v] = isset($tmphref[$k])?$tmphref[$k]:"";
            }
            $Categorylisttmp = array_unique ( $Categorytmp );

            //校验
            foreach($Categorylisttmp as $name=>$url)
            {
                $url2 = $url.'/ma_p_xquery.php?searchType=form&act=community&tradetype=sale&num=1&sort=1&currpage=1';
                // 首先获取下该分类下面的总页数
                $pageHtml = $this->curlmulit->remote ( $url2,null,false,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);
                $page = $pageHtml[$url2];
                $jsondata = json_decode($page,true);
                $result = isset($jsondata['results']) && $jsondata['results']?1:0;
                if($result)
                {
                    $curl = $url.'/ma_p_xquery.php?searchType=form&act=community&tradetype=sale&num=20&sort=1&currpage=';
                }else{
                    $curl = $url.'/map/xquery?searchType=form&act=community&tradetype=sale&num=20&sort=1&currpage=';
                }
                $Categorylist[$name] = $curl;
            }
            $mondata = array ();
            foreach ( $Categorylist as $name => $cid ) {
                $this->pools->set ( $poolname, $cid );
                $mondata [] = array (
                    'name' => $name,
                    'cid' => $cid,
                    'sid' => $sid
                );
            }
            /**
             * 写入mongodb category集合
             */
            $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
            if($mondata)
                $this->mongodb->batchinsert ( $collection_category_name, $mondata );
            unset($mondata);
        } else {
            $Categorylist = $this->mongodb->find ( $collection_category_name, array () );
            foreach ( $Categorylist as $obj ) {
                $cid = $obj ['cid'];
                $this->pools->set ( $poolname, $cid );
            }
        }
        echo "共收集到" . count ( $Categorylist ) . "个分类\n";
        unset($Categorylist);
        exit;
    }


    function CategroyJob() {
        header("Content-type: text/html; charset=utf-8");
        $name = $this->spidername . 'Category';
        $jobname = 'Category';
        $spidername = str_replace ( 'Spider', "", $this->spidername );
        $tmp = $this->pools->get ( $name );
        $jobs = array_values($tmp);
        $job = $jobs[0];
//        $job = 'http://esf.baidu.com/cs/map/xquery?range=1&act=community&tradetype=sale&num=20&sort=1&currpage=1';
        $poolname = $this->spidername . 'Item';
        $Category = Application::$_spider [elements::CATEGORY];
        $run = true;
        $currpage = 1;
        $uarr = explode("?",$job);
        $p = '/com\/(\w+)\/map/';
        preg_match($p,$job,$out);
        $city= isset($out[1]) && $out[1] ?$out[1]:"";
        $baseurl = $uarr[0];
        $pagesize = $Category [elements::CATEGORY_GROUP_SIZE];
        $result = array();
        do{
            $Categoryurl = $job.$currpage;
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
            $jsondata = json_decode($page,true);

            if(!isset($jsondata['results']) || !$jsondata['results'])
            {
                $run = false;
            }else
            {
                $communityids = array();
                foreach($jsondata['results'] as $k=>$v)
                {
                    $v['city'] = $city;
                    $this->mongodb->insert($name.'Tmp',$v);
                    $communityids[] = $v['communityid'];
                }
                //排除上海北京
                if(strstr($job,'xquery'))
                {

                    //获取房源
                    foreach($communityids as $k=>$communityid)
                    {
                        $s = 1;
                        $jobx = $baseurl.'?communityid='.$communityid.'&act=communityhouse&tradetype=sale&sort=1&page=';

                        $urlx =$jobx.'1';
                        $pageHtml = $this->curlmulit->remote ( $urlx,null,false,Application::$_spider [ elements::ITEMPAGECHARSET],Application::$_spider [elements::HTML_ZIP]);
                        $pagex = $pageHtml[$urlx];
                        $jsondatax = json_decode($pagex,true);
//                        if(!$jsondatax)
//                            echo $urlx."\n";
                        $totalpages = $jsondatax['total']+1;

                        do {
                            if ($totalpages < $pagesize) {
                                $e = $totalpages;
                            } else {
                                $e = $s + $pagesize;
                            }

                            $tmpurls = array ();
                            for($ii = $s; $ii < $e; $ii ++) {
                                $urlxx =$jobx.$ii;
//                                echo "page:".$ii."\n";
                                $tmpurls [$urlxx] = $urlxx;
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
                            foreach ( $pages as $rurl => $page ) {
                                $categorydata = json_decode($page,true);
//                                print_r($categorydata);
                                    if(isset($categorydata['data']) && $categorydata['data']){
                                        foreach($categorydata['data'] as $item)
                                        {
                                            $item[\elements::CATEGORY_ITEM_URL] = $item['houselink'];
                                            $item['job'] = $rurl;
                                            $result[] = $item;
                                            if($item[\elements::CATEGORY_ITEM_URL])
                                                $this->pools->set ( $poolname, $item[\elements::CATEGORY_ITEM_URL] );//将category_item_url加入任务池中 2014.12.20 22:32
                                            $this->mongodb->insert($this->spidername.'_category_list',$item);
                                        }
                                    }
                                unset($tmpurls[$rurl]);
                            }
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
                            $sleepseconds = rand(1,3);
                            sleep($sleepseconds);
                            $s = $s + $pagesize;
                        }while ( $s <= $totalpages );
                    }
                }
            }
            $currpage++;
            $sleepseconds = rand(1,3);
            sleep($sleepseconds);
        }while($run);
        $this->pools->deljob($name,$job);//加入删除备份任务机制
        $this->redis->decr ( $this->spidername . 'CategoryTotalCurrent' );
        $this->redis->hincrby ( $this->spidername . $jobname . 'Current',HOSTNAME,-1);
        exit;
    }
}