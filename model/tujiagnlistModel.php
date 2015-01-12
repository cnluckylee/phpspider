<?php 

class tujiagnlistModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'tujia_area';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $result = array();
        $mondata = $this->mongodb->find($collection,array());

        foreach($mondata as $item)
        {
            $sourceurl = $item['Category_Item_Url'];
            $arr = parse_url($sourceurl);
            $baseurl = $arr['scheme']."://".$arr['host'];
            $result[] = $baseurl.'/agent/agentnew/AloneRentHList.aspx?&agentid='.$item['Category_Item_Name'].'&housetype=rent&page=';
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

        if($mondata)
            $this->mongodb->batchinsert ( $collection_category_name, $mondata2 );
        unset($mondata);


    }
}