<?php 

class lejunewhouselistModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'lejunewhouse_area';
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
            $cid = $v['cid'];
            $url = 'http://www.leju.com/index.php?mod=sale_search&city='.$v['cid'].'&p=';
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
    //计算每个城市的经纪人数量
    function tojson($cname)
    {
//
        $data = $this->mongodb->find('lejunewhouse_area_copy',array());//212
//        $data2 = $this->mongodb->find('lejunewhouse_area_2',array());//255
//        $city1 = $city2 = array();
//        foreach($data as $k=>$v)
//        {
//            $city1[$v['cid']] = $v['name'];
//        }
//        foreach($data2 as $k=>$v)
//        {
//            $city2[$v['cid']] = $v['name'];
//        }
//
//        foreach($city1 as $c=>$v)
//        {
//            if(isset($city2[$c]) && $city2[$c] && $v==$city2[$c])
//            {
////                echo "find".$v."\n";
//                unset($city2[$c]);
//            }else{
//                echo "no find".$v."\n";
//            }
//        }
//        print_r($city2);
//        exit;
        $collection =  'lejunewhouselist_category_list';
        $str = '城市 抓取数量 domain'."\n";
        $filename = 'lejunewhouse_list_new.csv';
        $i=0;
        foreach($data as $q)
        {
            $keyword = 'city='.$q['cid'].'&';


            $regex = new MongoRegex("/.".$keyword."./");
            $total = $this->mongodb->count($collection,array("Category_Item_Url"=>$regex));
            $total = $total>0?$total:0;
            $str .= $q['name']." ".$total." ".$q['cid']."\n";
            echo $q['name']." ".$total." ".$keyword."\n";
        }
        $file = fopen($filename,"a+");
        fwrite($file,$str);
        fclose($file);
        exit("all over");
    }

    //计算每个城市的经纪人数量
    function tojsond()
    {
            $data = $this->mongodb->find('lejunewhouse_area',array());
            $collection = $cname = 'lejunewhouselist_category_list';
            $str = "城市\t楼盘名称\t物业类别\ttag\t户型\t均价\t可售套数\t报名人数\t优惠描述（重要）\t所属商圈\t地址\t环线位置\t开盘时间\t开发商\t评论总数\t物业公司\tURL"."\n";
            $filename = 'soufunnewhouse_list2.csv';
            $i=0;
            foreach($data as $q)
            {
                $tmp = parse_url($q['cid']);
                $domain = $tmp['host'];

                $regex = new MongoRegex("/.".$domain."./");
                $dd = $this->mongodb->find($collection,array("Category_Source_Url"=>$regex));
                foreach($dd as $k=>$v)
                {
                    $skuid = str_replace("/project/","",$v['Category_Item_Skuid']);
                    $d = $this->mongodb->findOne('Soufunnewhouse_List_Items',array('skuid'=>$skuid));
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

                        $d['Address'] = trim($v['Address']?$v['Address']:"无");
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

                }
                $i++;
                echo "has do".$i."\n";

            }

            $file = fopen($filename,"a+");
            fwrite($file,$str);
            fclose($file);
            exit("all over");
            exit;
    }
}