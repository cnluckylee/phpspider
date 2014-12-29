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

    public function  tojson($cname)
    {
        $cname = 'lejunewhouselist_category_list';
        $total = $this->mongodb->count($cname);
        $collection = 'lejunewhouselistItem';
        $s = 0;
        $limit = 1000;

        do {
            $mondata = $this->mongodb->find ( $cname, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );
            foreach($mondata as $item)
            {
                $url = $item['Category_Item_url'];
                if(strstr($url,"&city"))
                    $url2 = $url;
                else{
                    $url = str_replace("city","&city",$url);
                    $this->mongodb->update($cname,array('_id'=>$item['_id']),array('$set'=>array('Category_Item_url'=>$url)));
                    $url2 = $url;
                }
                $this->pools->set($collection,$url2);
            }
            echo "has load".$s."\n";
            $s +=$limit;
        }while($s<$total);
    }
}