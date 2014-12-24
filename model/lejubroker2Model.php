<?php 

class lejubroker2Model extends spiderModel
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
                $u2 = substr($u,0,strlen($u)-1);
                $url = $u2.'-n';
                $result[] = $url;
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
        $cname = 'leju_area';

        $data = $this->mongodb->find($cname,array());
        $filename = 'leju_Data.csv';
        $str = "名称 网站数量 抓取数量 URL"."\n";
        foreach($data as $i)
        {
            $str.= $i['name']." "." ".$i['cid']."\n";
        }
        $file = fopen($filename,"a+");
        fwrite($file,$str);
        fclose($file);
    }

}