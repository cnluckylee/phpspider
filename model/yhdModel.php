<?php 

class yhdModel extends spiderModel
{
    public function CategroyJob()
    {
        $name = $this->spidername . 'Category';
        $spidername = str_replace('Spider', "", $this->spidername);
        $job = $this->pools->get ( $name );
        $poolname = $this->spidername . 'Item';
        $Category = Application::$_spider ['Category'];
        
        $Categoryurl = str_replace ('#job', $job, $Category['Category_List_URL']);
        
        // 首先获取下该分类下面的总页数
        $pageHtml = $this->curlmulit->remote ( $Categoryurl, null, false);
        //$pageHtml = file_get_contents($Categoryurl);
        if(!$pageHtml)
        {
            $this->autostartitemmaster();
            $this->redis->decr ( $this->spidername .'CategoryCurrent' );
            $this->log->errlog(array('job'=>$job,'Categoryurl'=>$Categoryurl,'error'=>2,'addtime'=>date('Y-m-d H:i:s')));
            exit;
        }
        $preg_pagetotals = $Category ['Category_List_Preg'];
//         preg_match_all( $preg_pagetotals, $pageHtml[0], $match_pagetotals );
        preg_match( $preg_pagetotals, $pageHtml[0], $match_pagetotals );
        $totalpages = $match_pagetotals[0] ? $match_pagetotals[$Category['Category_List_Match']] : 0;
        $s = isset($Category['Category_Page_Start'])?$Category['Category_Page_Start']:0;
        $pagesize = $this->runpages;
        if ($totalpages > 0) {
            // 循环获取商品的url地址
            do {
                if ($totalpages < $pagesize) {
                    $e = $totalpages;
                } else {
                    $e = $s + $pagesize;
                }
                $tmpurls = array ();//$pages = array();
                for($i = $s; $i < $e; $i ++) {
                    $url = $Category ['CATEGORY_LIST_Pages_URL'];
                    $url = str_replace (array('#job', '#i'), array($job, $i), $url);
                    $tmpurls [$url] = $url;
                    //$pages[$url] = file_get_contents($url);
                }
                $pages = $this->curlmulit->remote ( $tmpurls, null, false);
                /**
                 * 能否抓去到数据检测,此代码保留
                 */
                if($s==0 && count($pages)==0)
                {
                    $this->master('Item');
                    $this->redis->decr ( $this->spidername .'CategoryCurrent' );
                    $this->log->errlog(array('job'=>$job,'Categoryurl'=>$Categoryurl,'error'=>1,'addtime'=>date('Y-m-d H:i:s')));
                    exit;
                }
                $preg = $Category ['Category_List_Goods_Preg'];
                $match = $Category ['Category_List_Goods_Match'];
                foreach ( $pages as $rurl =>$page ) {
                    preg_match_all ( $preg, $page, $match_out );
                    $item_ids = isset ( $match_out [$match] ) ? $match_out [$match] : "";
                    $item_ids = array_unique(array_merge($item_ids, $this->_getMoreProductsItemIds($url)));// print_r($item_ids);exit;
                    // 加入itemjobs
                    foreach ( $item_ids as $id ) {
                        $this->pools->set ( $poolname, 'http://item.yhd.com/item/' . $id );
                    }
                }
                $s = $s + $pagesize;
        
            } while ( $s <= $totalpages );
        }
        $jobs1 = $this->redis->get($this->spidername . 'CategoryCurrent');
        $this->redis->decr ( $this->spidername .'CategoryCurrent' );
        $jobs2 = $this->redis->get($this->spidername .'CategoryCurrent');
        $this->log->msglog(array('job'=>$job,'runjobs1'=>$jobs1,'runjobs2'=>$jobs2,'addtime'=>date('Y-m-d H:i:s')));
        $this->autostartitemmaster();
        exit;
    }
    
    private function _getMoreProductsItemIds($url, $is_second_time_call = false)
    {
        $item_ids = array();
        
        $replace_string = $is_second_time_call ? '\1searchPage' : '\1searchVirCateAjax';
        $replace_url = preg_replace('/(ctg\/)s2/', $replace_string, $url);
        if ($replace_url && $replace_url != $url)
        {
            $url_content = file_get_contents($replace_url . '?isGetMoreProducts=1&moreProductsDefaultTemplate=0');
            if ($url_content !== false)
            {
                $url_content = str_replace('\\"', '"', $url_content);
                $match_count = preg_match_all(Application::$_spider ['Category']['Category_List_Goods_Preg'], $url_content, $ids_matches);
                if ($match_count == 0 && !$is_second_time_call)
                {
                    $this->_getMoreProductsItemIds($url, true);
                }
                else if ($match_count > 0)
                {
                    $item_ids = array_unique($ids_matches[1]);
                }
            }
            else 
            {
                if (!$is_second_time_call)
                {
                    $this->_getMoreProductsItemIds($url, true);
                }
            }
        }
        
        return $item_ids;
    }
}