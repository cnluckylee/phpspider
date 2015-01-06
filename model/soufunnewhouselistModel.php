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

    //计算每个城市的经纪人数量
    function tojson($cname)
    {
        $data = $this->mongodb->find('soufunnewhouse_area',array());
        $collection = $cname = 'soufunnewhouselist_category_list';
        $str = "城市\t楼盘名称\t物业类别\ttag\t户型\t均价\t可售套数\t报名人数\t优惠描述（重要）\t所属商圈\t地址\t环线位置\t开盘时间\t开发商\t评论总数\t物业公司\tURL"."\n";
        $filename = 'soufunnewhouse_list2.csv';
        $i=0;
        foreach($data as $q)
        {
            $tmp = parse_url($q['cid']);
            $domain = $tmp['host'];
//         $domain = 'newhouse.bj.fang.com';
            $regex = new MongoRegex("/.".$domain."./");
            $dd = $this->mongodb->find($collection,array("Category_Source_Url"=>$regex));
            foreach($dd as $k=>$v)
            {
                $d = $this->mongodb->findOne('Soufunnewhouse_List_Items',array('source_url'=>$v['Category_Item_Url']));
                if($d)
                {
                    $area = $v['Category_Item_Area'];
                    $p='/\[(.*)\]/';
                    preg_match($p,$area,$out);
                    $s = $out[1];
                    $s = $s?$s:"无\t";
                    $sq = str_replace(array(" ","\t"),",",trim(isset($d['promotion']['所属商圈'])?$d['promotion']['所属商圈']:""));
                    $wy = str_replace(array(" ","\t"),",",trim(isset($d['promotion']['物业公司'])?$d['promotion']['物业公司']:""));
                    $kp = str_replace(array(" ","\t"),",",trim(isset($d['promotion']['开盘时间'])?$d['promotion']['开盘时间']:""));
                    $wylb = str_replace(array(" ","\t"),",",trim(isset($d['promotion']['物业类别'])?$d['promotion']['物业类别']:""));
                    $d['Discount'] = str_replace(" ",",",trim($d['Discount']?$d['Discount']:"无\t"));

                    $d['Address'] = trim($v['Category_Item_Area']?$v['Category_Item_Area']:"无\t");
                    $v['PropertyType'] = trim($v['PropertyType']?$v['PropertyType']:"无\t");
                    $v['Category_Item_DPrice'] = $v['Category_Item_DPrice']?$v['Category_Item_DPrice']:"无\t";
                    $d['Apartment'] = $d['Apartment']?$d['Apartment']:"无\t";
                    $d['AvePrice'] = trim($d['AvePrice']?$d['AvePrice']:"无\t");
                    $d['sales'] = trim($d['sales']?$d['sales']:"无\t");
                    $d['isbn'] = trim($d['isbn']?$d['isbn']:"无\t");
                    $d['Developer'] = trim($d['Developer']?$d['Developer']:"无\t");
                    $d['all_comment_number'] = trim($d['all_comment_number']?$d['all_comment_number']:0);
                    $v['City'] = trim($v['City']?$v['City']:"无\t");
                    $tag = array();
                    foreach($v['Category_Item_DPrice'] as $k=>$vs)
                    {
                        if($vs)
                        $tag[] = $vs;
                    }
                    $hx = array();
                    foreach($d['Apartment'] as $kk=>$vv)
                    {
                        if($vv)
                            $hx[] = $vv;
                    }
                    $hxs = trim(join(",",$hx));
                    if(!$hxs)
                        $hxs = "无\t";


                    $tags = trim(join(",",$tag));
                    if(!$tags)
                        $tags = "无\t";
                    if(!$wylb)
                    $wylb = "无\t";

                    if(!$wy)
                        $wy = "无\t";

                    $str .= $v['City']."\t".$v['Category_Item_Name']."\t".$wylb."\t".$tags."\t".$hxs."\t".$d['AvePrice']."\t".$d['sales'];
                    $str .="\t".$d['isbn']."\t".$d['Discount']."\t".$sq."\t".$d['Address']."\t".$s."\t".$kp."\t".$d['Developer']."\t".$d['all_comment_number'];
                    $str .="\t".$wy."\t".$v['Category_Item_Url'];
                    $str .="\n";
                }else
                {
                    echo $v['Category_Item_Url']."\n";
                }

//                if($i>20)
//                {
//                    $file = fopen($filename,"a+");
//                    fwrite($file,$str);
//                    fclose($file);
//                    exit("all over");
//                }

            }
            $i++;
            echo "has do".$i."\n";

        }

        $file = fopen($filename,"a+");
        fwrite($file,$str);
        fclose($file);
        exit("all over");
        exit;

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
        $datas = $this->redis->hgetall('tmptotal');

        foreach($data as $q)
        {
            $domain = str_replace(array("http://","/"),"",$q['cid']);
//            $regex = new MongoRegex("/.".$domain."./");
//            $total = $this->mongodb->count($collection,array("Category_Item_Url"=>$regex));
            $total = $datas[$domain];
            $total = $total>0?$total:0;
            unset($datas[$domain]);
            $str .= $q['name']." ".$total." ".$domain.'/agenthome/'."\n";
            echo $q['name']." ".$total." ".$domain.'/agenthome/'."\n";
        }
        print_r($datas);
//        foreach($datas as $k=>$v)
//        {
//            $str .= $k." ".$v."\n";
//        }

    }
}