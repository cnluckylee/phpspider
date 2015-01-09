<?php 

class soufunbrokerModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'Soufun_Area_Items';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
//        $regex = new MongoRegex("/.esf.nb.fang./");
        $data = $this->mongodb->find($collection,array());//"source_url"=>$regex


        /**
         * 写入mongodb category集合
         */

        $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
        foreach($data as $k=>$v)
        {

            $result = array();
            $baseurl = str_replace(array("-i31-j310/","-i31-j310"),"",$v['source_url']);
            $result[] = $baseurl.'-j3100-i3';
            $companys = array_merge($v['dprice'],$v['characters']);

            if($v['price_url'])
            {
                $urls = array_unique($v['price_url']);

                $companys = array_unique($companys);
                foreach($urls as $u)
                {
                    $baseurl2 = str_replace(array("/-j310-i31","/-i31-j310/","-i31-j310"),"",$u);
                    $result[] = $baseurl2.'-j3100-i3';
//                    foreach($companys as $kk=>$vv)
//                    {
//                        if($vv && $vv!="不限")
//                        {
//                            $jjgs = '-c5'.urlencode(mb_convert_encoding($vv, 'GB2312', 'UTF-8'));
////                            $jjgs = '-c5'.$vv;
//                            $result[] = $baseurl2.$jjgs.'-i3';
//                        }else if($vv!="不限"){
//                            $result[] = $baseurl2.'-j310-i3';
//                        }
//                    }
                }
            }else{
//                foreach($companys as $kk=>$vv)
//                {
//                    if($vv && $vv!="不限"){
//                        $jjgs = '-c5'.urlencode(mb_convert_encoding($vv, 'GB2312', 'UTF-8'));
////                        $jjgs = '-c5'.$vv;
//                        $result[] = $baseurl.$jjgs.'-j310-i3';
//                    }
//                }
            }
            $Categorylist = array_unique ( $result );
            $mondata2 = array ();
            foreach ( $Categorylist as $name => $cid ) {
                $this->pools->set ( $poolname, $cid );
                $mondata2 = array (
                    'name' => $name,
                    'cid' => $cid,
                    'sid' => $sid
                );
                $this->mongodb->insert ( $collection_category_name, $mondata2 );
            }
            unset($result);
        }
        echo "do over"."\n";
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
        $poolname = $this->spidername . 'Item';
        $Category = Application::$_spider [elements::CATEGORY];
        $xpath = $Category [elements::CATEGORY_MATCHING];
        if(isset($Category [elements::TRANSFORM]) && $Category [elements::TRANSFORM] === false)
            $Categoryurl = $job.$Category [elements::TRANSFORMADDSPECIL];
        else
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

        if(isset(Application::$_spider [elements::TOTALPAGES])&&Application::$_spider [elements::TOTALPAGES]>0)
            $totalpages = Application::$_spider [elements::TOTALPAGES];
        else{
            $preg_pagetotals = $Category [elements::CATEGORY_LIST_PREG];
            if(strtolower($xpath) == 'xpath')
            {
                $totalpages = $this->curlmulit->getRegexpInfo($preg_pagetotals,$pageHtml [$Categoryurl],$Category [elements::CATEGORY_LIST_MATCH]);
            }else{
                preg_match ( $preg_pagetotals, $pageHtml [$Categoryurl], $match_pagetotals );
                foreach($match_pagetotals as $k=>$v)
                {
                    $match_pagetotals[$k] = trim($v);
                }
                $totalpages = $match_pagetotals ? $match_pagetotals [$Category [elements::CATEGORY_LIST_MATCH]] : 0;
            }
        }
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
                        $spidermodel = new $Productmodel ( $this->spidername, $rurl, $page, $Category [elements::CATEGORY_ITEM_PREG] );
                        $categorydata = $spidermodel->CategoryToArray ( );
                        //监控是否满足数量100
                        $pagecount = count($categorydata);
                        if($pagecount<50)
                        {
                            $s = 101;

                        }
                        if($categorydata){
                            foreach($categorydata as $item)
                            {
                                $item['Category_Source_Url'] = $rurl;
                                $item['job'] = $job;
                                if($item[\elements::CATEGORY_ITEM_URL])
                                    $this->pools->set ( $poolname, $item[\elements::CATEGORY_ITEM_URL] );//将category_item_url加入任务池中 2014.12.20 22:32
                                $this->mongodb->insert($this->spidername.'_category_list',$item);
                            }
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
        exit ();
    }


/*
    function tojson($cname)
    {
        $total = $this->mongodb->count($cname);
        $collection = 'Soufun_Items';
        $s = 0;
        $limit = 1000;
        $filename = $cname.'.log';
        do {
            $mondata = $this->mongodb->find ( $cname, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            $str = '昵称 姓名 ID 星级 所属公司 服务地区 特长 认证';

            foreach($mondata as $item)
            {
                if(empty($item['Category_Item_OPrice'])){
                    $find = array('skuid'=>(string)$item['Category_Item_Skuid']);

                    $d = $this->mongodb->findOne($find);
                    if($d){
                        $item['Category_Item_OPrice'] = $d['source_category_name'];

                    }
                }
               $str .= $item['Category_Item_Skuid']." ".$item['Category_Item_Name'];
               $str .= " ".$item['Category_Item_Url']." ".$item['Category_Item_Area'];
                $str .= " ".$item['Category_Item_OPrice'];
                $str .=" ".implode(",",$item['Category_Item_DPrice']).' '." ".implode(",",$item['Category_Item_Hot']);
                $str .=" ".implode(",",$item['Category_Item_Reviews']);
                $str .="\n";
            }
            $file = fopen($filename,"a+");
            fwrite($file,$str);
            fclose($file);
            $s +=$limit;
        }while($s<$total);
    }
    function tojson($cname)
    {
        $cname = 'soufunbroker_err_log';
        $total = $this->mongodb->count($cname);
        $collection = 'soufunbrokerCategory';
        $s = 0;
        $limit = 1000;
        do {
            $mondata = $this->mongodb->find ( $cname, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            foreach($mondata as $v)
            {
               $job = $v['job'];
               $this->pools->set($collection,$job);
            }
            $s +=$limit;
            echo "has load:".$s."\n";
        }while($s<$total);

        exit("all over");
    }
*/
    //计算每个城市的经纪人数量
    function tojson($cname)
    {
        $this->tojsond();exit;
        $data = $this->mongodb->find('soufun_area',array());
        $collection = $cname = 'soufunbroker_category_list';
        $str = '城市 抓取数量 URL'."\n";
        $filename = 'soufun_agent.csv';

        $i=0;
//        foreach($data as $q)
//        {
//            $domain = str_replace(array("http://","/"),"",$q['cid']);
//            $regex = new MongoRegex("/.".$domain."./");
//            $total = $this->mongodb->count($collection,array("Category_Item_Url"=>$regex));
//            $str .= $q['name']." ".$total." ".$domain.'/agenthome/'."\n";
//            echo $q['name']." ".$total." ".$domain.'/agenthome/'."\n";
////            if($i>10)
////                break;
////            $i++;
//        }
//        $total = $this->mongodb->count($collection);
//        $s = 0;
//        $limit = 1000;
//        do {
//            $mondata = $this->mongodb->find ( $cname, array (), array (
//                "start" => $s,
//                "limit" => $limit
//            ) );
//            foreach($mondata as $v)
//            {
//                $url = $v['Category_Item_Url'];
//                $tmp = parse_url($url);
//                $domainurl = $tmp['host'];
//                $this->redis->hincrby('tmptotal',$domainurl,1);
//            }
//            $s +=$limit;
//            echo "has load:".$s."\n";
//        }while($s<$total);
//        $datas = $this->redis->hgetall('tmptotal');

        foreach($data as $q)
        {
            $arr = parse_url($q['cid']);
            $domain = $arr['host'];
            $regex = new MongoRegex("/.".$domain."./");
            $total = $this->mongodb->count($collection,array("Category_Item_Url"=>$regex));
//            $total = $datas[$domain];
            $total = $total>0?$total:0;
//            unset($datas[$domain]); 
            $str .= $q['name']." ".$total." ".$domain.'/agenthome/'."\n";
            echo $q['name']." ".$total." ".$domain.'/agenthome/'."\n";
        }
//print_r($datas);
//        foreach($datas as $k=>$v)
//        {
//            $str .= $k." ".$v."\n";
//        }
        $file = fopen($filename,"a+");
        fwrite($file,$str);
        fclose($file);
        exit("all over");
    }

    //计算每个城市的经纪人数量
    function tojsond()
    {
        $data = $this->mongodb->find('soufun_area',array());
        $collection = $cname = 'soufunbroker_category_list';
        $str = "城市\t经纪人姓名\t所属公司\t所属门店\t联系电话\t服务区县\t服务商圈\t职业特长\t黄钻数\t是否为专家\t是否通过职业认证\t";
        $str .="是否通过身份认证\t是否通过名片认证\tURL\t"."\n";
        $filename = 'soufun_broker_detail.csv';
        $i=0;
        $citys = array();
        $tmp = $this->mongodb->find('soufun_area',array());
        foreach($tmp as $k=>$v)
        {
            $arr = parse_url($v['cid']);
            $domain = $arr['host'];
            $citys[$domain] = $v['name'];
        }
        $total = $this->mongodb->count($cname);
        $s = 0;
        $limit = 1000;
        do {
            $mondata = $this->mongodb->find ( $cname, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            foreach($mondata as $v)
            {
                $url = $v['Category_Item_Url'];
                $skuid = $v['Category_Item_Skuid'];
                $d = $this->mongodb->findOne("soufunbroker_Items",array('skuid'=>$skuid));
                if($d)
                {
                    $tmp = parse_url($url);
                    $domain = $tmp['host'];
                    $ct = $citys[$domain];
                    $ssgs = $d['source_category_name']?$d['source_category_name']:"无";
                    $ssmd = $d['dprice']?$d['dprice']:"无";
                    $tel = $d['source_category_id']?$d['source_category_id']:"无";
                    $qy = $d['brand_id']?$d['brand_id']:"无";
                    $sq = $d['brand_name']?$d['brand_name']:"无";
                    $tc = $v['Category_Item_Hot']?$v['Category_Item_Hot']:"无";
                    $zj = $v['Category_Item_Sale']?'是':'否';
                    $zyrz = in_array("已通过职业资格认证",$v['Category_Item_Reviews'])?"是":"否";
                    $sfrz = in_array("已通过身份认证",$v['Category_Item_Reviews'])?"是":"否";
                    $mprz = in_array("已通过名片认证",$v['Category_Item_Reviews'])?"是":"否";
                    $zs = $v['Category_Item_Area']?$v['Category_Item_Area']:"无";
                    $str .= $ct."\t".$v['UserName']."\t".$ssgs."\t".$ssmd."\t".$tel."\t".$qy."\t".$sq."\t".$tc."\t".$zs."\t".$zj."\t";
                    $str .= $zyrz."\t".$sfrz."\t".$mprz."\t".$url."\t";
                }

            }
            $s +=$limit;
            echo "has load:".$s."\n";
            if($s>1000)
            {
                $file = fopen($filename,"a+");
                fwrite($file,$str);
                fclose($file);
            }
        }while($s<$total);




        exit("all over");
    }

}