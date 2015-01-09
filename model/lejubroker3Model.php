<?php 

class lejubroker3Model extends spiderModel
{
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
            $tmp = $this->curlmulit->remote( $Category_URL , null, false, Application::$_spider[elements::CHARSET],Application::$_spider [ elements::ITEMPAGECHARSET]);
            $page = $tmp[$Category_URL];
            $Categorytmp = array();
            $preg = '//div[@class="city_list"]/a/@href';
            $tmphref = $this->curlmulit->getRegexpInfo2($preg,$page);
            $preg = '//div[@class="city_list"]/a/text()';
            $tmptext = $this->curlmulit->getRegexpInfo2($preg,$page);
            foreach($tmptext as $k=>$v)
            {
                $url = isset($tmphref[$k])?$tmphref[$k]:"";
                $url .='/agent/n';
                $Categorytmp[$v] = $url;
            }
            $Categorylist = array_unique ( $Categorytmp );
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