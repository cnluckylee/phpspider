<?php 

class lejunewhouselistModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'lejunewhousearea_category_list';
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
            $cid = $v['Category_Item_Url'].'&p=';
            $name = $v['Category_Item_Name'];
            $tmp = parse_url($cid);
            parse_str($tmp['query'],$parr);
            $url = 'http://www.leju.com/index.php?mod=sale_search&city='.$parr['city'].'&district='.urlencode($parr['district']).'&&p=';
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
            $data = $this->mongodb->find('soufunnewhouse_area',array());
            $collection = $cname = 'soufunnewhouselist_category_list';
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