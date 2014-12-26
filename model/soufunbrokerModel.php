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
            $result[] = $baseurl.'-j310-i3';
            $companys = array_merge($v['dprice'],$v['characters']);

            if($v['price_url'])
            {
                $urls = array_unique($v['price_url']);

                $companys = array_unique($companys);
                foreach($urls as $u)
                {
                    $baseurl2 = str_replace(array("/-j310-i31","/-i31-j310/","-i31-j310"),"",$u);
                    $result[] = $baseurl2.'-j310-i3';
                    foreach($companys as $kk=>$vv)
                    {
                        if($vv && $vv!="不限")
                        {
                            $jjgs = '-c5'.urlencode(mb_convert_encoding($vv, 'GB2312', 'UTF-8'));
//                            $jjgs = '-c5'.$vv;
                            $result[] = $baseurl2.$jjgs.'-i3';
                        }else if($vv!="不限"){
                            $result[] = $baseurl2.'-j310-i3';
                        }
                    }
                }
            }else{
                foreach($companys as $kk=>$vv)
                {
                    if($vv && $vv!="不限"){
                        $jjgs = '-c5'.urlencode(mb_convert_encoding($vv, 'GB2312', 'UTF-8'));
//                        $jjgs = '-c5'.$vv;
                        $result[] = $baseurl.$jjgs.'-j310-i3';
                    }
                }
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
*/
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

}