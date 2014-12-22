<?php 

class soufunnewhouseareaModel extends spiderModel
{

    /**
     * 获取分类数据
     */
    function getCategory() {
        header("Content-type: text/html; charset=utf-8");
        $Category = Application::$_spider ['Category'];
        $thistimerun = isset ( $Category ['Category_Run'] ) ? $Category ['Category_Run'] : 1;
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        // 清理Category现场
        $this->pools->del ( $poolname );
        $this->redis->delete ( $this->spidername . 'CategoryCurrent' );
        $this->redis->delete ( $this->spidername . 'ItemCurrent' );
        $this->redis->delete ( $this->spidername . 'Item' );
        $this->redis->delete ( $this->spidername . 'ItemJobRun' );
        // 判断本次是否重新抓取分类数据

        if ($thistimerun) {
            $Category_URL = $Category[elements::CATEGORY_URL];
//			$page = file_get_contents ( $Category_URL );
            $tmp = $this->curlmulit->remote( $Category_URL , null, false, Application::$_spider[elements::CHARSET],Application::$_spider [ elements::ITEMPAGECHARSET]);

            $page = $tmp[$Category_URL];
            $preg = $Category [elements::CATEGORY_MATCH_PREG];
            $matchnum = $Category [elements::CATEGORY_MATCH_MATCH];
            $Categorytmp = array();
            preg_match_all ( $preg, $page, $match );
            if (is_array ( $matchnum )) {
                $name = $matchnum ['name'];
                $cid = $matchnum ['cid'];
                $Categorytmp = array_combine ( $match [$name], $match [$cid] );
            } else {
                $Categorytmp = $match [$matchnum];
            }
            // $Categorylist = array_slice($Categorytmp,1,12);
            $Categorylist = array_unique ( $Categorytmp );
//            }

            $mondata = array ();
            foreach ( $Categorylist as $name => $cid ) {
                $cid = str_replace("soufun","fang",$cid);
                $cid = str_replace("/","",$cid);
                $cid = 'http://newhouse.'.$cid.'/house/s/list/';
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
        } else {
            $Categorylist = $this->mongodb->find ( $collection_category_name, array () );
            foreach ( $Categorylist as $obj ) {
                $cid = $obj ['cid'];
                $this->pools->set ( $poolname, $cid );
            }
        }
        echo "共收集到" . count ( $Categorylist ) . "个分类\n";
        unset($Categorylist);
        exit;
    }
}