<?php

class soufunesflistModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'soufunbroker_category_list';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $result = array();
        $total = $this->mongodb->count($collection);
        $s = 0;
        $limit = 1000;
        /**
         * 写入mongodb category集合
         */
        if($total>0)
            $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
        do {
            $mondata2 = $result = array ();
            $mondata = $this->mongodb->find ( $collection, array (), array (
                "start" => $s,
                "limit" => $limit
            ) );

            foreach($mondata as $item)
            {
                $sourceurl = $item['Category_Item_Url'];
                $arr = parse_url($sourceurl);
                $baseurl = $arr['scheme']."://".$arr['host'];
                $result[] = $baseurl.'/agent/agentnew/AloneesfHList.aspx?&agentid='.$item['Category_Item_Name'].'&pricemax=&page=';
            }
            $Categorylist = array_unique ( $result );

            foreach ( $Categorylist as $name => $cid ) {
                $this->pools->set ( $poolname, $cid );
                $mondata2 [] = array (
                    'name' => $name,
                    'cid' => $cid,
                    'sid' => $sid
                );
            }
            $s +=$limit;
            if($mondata)
                $this->mongodb->batchinsert ( $collection_category_name, $mondata2 );
            unset($mondata);

        }while($s<$total);
        exit;
    }
}