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
        $collection = 'lejubroker2Item';
        $s = 0;
        $limit = 1000;
        $companys = array();
        $tmp = array();
        do {
            $mondata = $this->mongodb->find ( $cname, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            foreach($mondata as $item)
            {
               $str = $item['Category_Item_Url'];
                $arr = explode("-",$str);
                $this->redis->hset($collection.'Bak',$arr[0].'-4',1);
            }
            $s +=$limit;
            echo "has load:".$s."\n";
        }while($s<$total);

        exit("over");
    }

}