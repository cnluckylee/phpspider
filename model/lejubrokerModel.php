<?php 

class lejubrokerModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'Leju_Area_Items';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
//        $regex = new MongoRegex("/sh-./");
        $data = $this->mongodb->find($collection,array());
        $result = array();
        /**
         * 写入mongodb category集合
         */
        $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
        foreach($data as $k=>$v)
        {
            $v['price_url'] = array_unique($v['price_url']);
            $v['dprice'] = array_unique($v['dprice']);
//            echo  count($v['price_url'])." ". count($v['dprice']);
            if(!$v['price_url'])
            {
                $v['price_url'] = $v['dprice'];
                unset($v['dprice']);
            }
            foreach($v['price_url'] as $u)
            {
                $tmp =  explode("agent/",$u);
                $tmp = str_replace("/","",$tmp[1]);
                $tmp = explode("-",$tmp);
                $a = isset($tmp[0])?'-'.$tmp[0]:"";
                $b = isset($tmp[1])?'-'.$tmp[1]:"";
                foreach($v['dprice'] as $kk=>$vv)
                {
                    if($u!=$vv)
                        $u2 = substr($vv,0,strlen($vv)-1);
                    else
                        $u2 = $vv;
                    $result[] = $u2.$b.'-n';
                }
                if(!isset($v['dprice']))
                {
                    if($a || $b)
                        $url = substr($u,0,strlen($u)-1);
                    else{
                        $url = $u;
                    }
                    $url = $url.'-n';
                    $result[] = $url;
                }

            }
            $Categorylist = array_unique ( $result );
//            print_r($Categorylist);
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
        $cname = 'lejubroker2_category_list';
        $total = $this->mongodb->count($cname);
        $cityt = $this->mongodb->find('leju_area',array());
        $city = array();
        foreach($cityt as $k=>$v)
        {
            $s = str_replace(array("http://",".sina.com.cn"),"",$v['cid']);
            $city[$s] = $v['name'];
        }

        $collection = 'lejubrokerItem';
        $s = $i= 0;
        $limit = 1000;
        $companys = array();
        $tmp = array();
        $filename = 'lejubroker_detail.csv';
        $str = "城市\t名称\t服务区域\t服务楼盘\t所在门店\t手机\t出租数量\t出售数量\t评分等级\t诚信认证\t注册时间\tURL"."\n";
        $i=0;
        do {
            $mondata = $this->mongodb->find ( $cname, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            foreach($mondata as $item)
            {

                    $itemurl = $item['Category_Item_Skuid'];
                    $arr = explode("-",$itemurl);
                    $p = '/shop\/(\d+)/';
                    preg_match($p,$itemurl,$out);

                    $p2 = '/http:\/\/(\w+).esf/';
                    preg_match($p2,$itemurl,$out2);
                    $domain = isset($out2[1])?$out2[1]:"";
                    $skuid = isset($out[1])?$out[1]:"";

                    if(!$domain)
                    {
                        $p3 = '/com\/(\w+)\/shop/';
                        preg_match($p3,$itemurl,$out3);
                        $domain = isset($out3[1])?$out3[1]:"";
                    }
                    $skuid = $domain.'-'.$skuid;
                    $scity = $city[$domain.'.esf'];
                    $d = $this->mongodb->findOne('lejubroker_Items',array('skuid'=>$skuid));
                    if(!$d)
                    {
                        $this->redis->sadd($collection,$arr[0].'-4');
                        $this->redis->hset('lejubrokerItemBak',$arr[0].'-4',1);
                        echo "add :".$skuid."\n";
                    }else{
                        $loupan = $store= $regtime = "无";
                        $ServiceArea = isset($item['ServiceArea'])?$item['ServiceArea']:"\t";
                        if(isset($item['Category_Item_Area']) &&  $item['Category_Item_Area']){
                            $loupan = join(",",$item['Category_Item_Area']);
                            if(strstr($loupan,"所在楼盘") || strstr($loupan,"手机"))
                                $loupan = "无";
                            if(strstr($loupan,"门店")){
                                $store = $loupan;
                                $loupan = "无";
                            }

                        }
                        $store = $store=='无' && isset($item['Stores']) && strstr($item['Stores'],"门店")?$item['Stores']:"无";
                        $tel = trim(isset($item['Tel'])?$item['Tel']:"无");
                        $sales = isset($item['Category_Item_Sale'])?$item['Category_Item_Sale']:"无";
                        $maichu = isset($item['Category_Item_Hot'])?$item['Category_Item_Hot']:"无";
                        $jp = isset($item['Category_Item_Mprice'])?$item['Category_Item_Mprice']:"无";
                        $rz = isset($item['Category_Item_OPrice'])?$item['Category_Item_OPrice']:"无";
                        $regtime = isset($d['isbn']) && $d['isbn']?$d['isbn']:"无";
                        $str .= $scity."\t".$item['UserName']."\t".$ServiceArea."\t".$loupan."\t".$store."\t".$tel."\t";

                        $str .= $sales."\t".$maichu."\t".$jp."\t".$rz."\t".$regtime."\t".$item['Category_Item_Skuid']."\t"."\n";

                        $i++;
//                       if($i>2000)
//                        {
//                            $file = fopen($filename,"a+");
//                            fwrite($file,$str);
//                            fclose($file);
//                            exit("all over");
//                        }
                    }
                }

            echo "has do".$i."\n";

            $s +=$limit;
            echo "has load:".$s."\n";
        }while($s<$total);
        $file = fopen($filename,"a+");
        fwrite($file,$str);
        fclose($file);
        exit("all over");

    }

}