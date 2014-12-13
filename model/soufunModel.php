<?php 

class soufunModel extends spiderModel
{
    public function  getCategory()
    {
        $collection = 'Soufun_Area_Items';
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $data = $this->mongodb->find($collection,array());
        $result = array();
        foreach($data as $k=>$v)
        {
            $baseurl = $v['barcode'];
            $baseurl = substr($baseurl,0,strlen($baseurl)-1);
            $v['promotion'] = array_unique($v['promotion']);
            $v['dprice'] = array_unique($v['dprice']);
            foreach($v['promotion'] as $u)
            {
                if($u)
                $u = substr($u,0,strlen($u)-1);
                $u = str_replace('-i31','',$u);
                foreach($v['dprice'] as $kk=>$vv)
                {
                    $jjgs = '-c5'.$vv;
//                foreach($v['oprice'] as $kkk=>$vvv)
//                {
//                    $zytc = '-j5'.$vvv;


//                }


                    $result[] = $baseurl.$u.urlencode(mb_convert_encoding($jjgs, 'GB2312', 'UTF-8')).'-i3';

               }
//                if($u)
                $result[] = $baseurl.$u.'-i3';

            }

        }
        $Categorylist = array_unique ( $result );


        $mondata = array ();
        foreach ( $Categorylist as $name => $cid ) {
            $this->pools->set ( $poolname, $cid );
            $mondata [] = array (
                'name' => $name,
                'cid' => $cid,
                'sid' => $sid
            );
        }
        /**
         * 写入mongodb category集合
         */
        $this->mongodb->remove ( $collection_category_name, array () ); // 删除原始数据，保存最新的数据
        if($mondata)
            $this->mongodb->batchinsert ( $collection_category_name, $mondata );
        unset($mondata);
        echo "共收集到" . count ( $Categorylist ) . "个分类\n";
        unset($Categorylist);
        exit;
    }
}