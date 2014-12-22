<?php 

class lejubrokerModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'Leju_Area_Items';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $data = $this->mongodb->find($collection,array());

        $result = array();
        /**
         * 写入mongodb category集合
         */
        $this->mongodb->remove ( $collection_category_name, array ()); // 删除原始数据，保存最新的数据
        foreach($data as $k=>$v)
        {
            $v['price_url'] = array_unique($v['price_url']);
            $v['dprice'] = array_unique($v['dprice']);
            foreach($v['price_url'] as $u)
            {

                $tmp =  explode("agent/",$u);
                $tmp = str_replace("/","",$tmp[1]);
                $tmp = explode("-",$tmp);
                $base2 = isset($tmp[0])?$tmp[0]:"";
                $base3 = isset($tmp[1])?$tmp[1]:"";
                foreach($v['dprice'] as $kk=>$vv)
                {
                    $u = substr($vv,0,strlen($vv)-1);
                    $result[] = $u.'-'.$base3.'-n';
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
        $cname = 'Soufun_Items';
        $total = $this->mongodb->count($cname);
        $collection = 'soufun_Items';
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
                $skuid = $item['source_category_name'];
                $companys[$skuid] += 1;
            }
            $s +=$limit;
            echo "has load:".$s."\n";
        }while($s<$total);
        $str = '';
        foreach($companys as $name=>$count)
        {
            $str .=$name.' '.$count."\n";
        }
        $file = fopen("test.txt","w");
        fwrite($file,$str);
        fclose($file);
        exit("over");
    }

}