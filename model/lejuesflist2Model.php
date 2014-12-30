<?php

class lejuesflist2Model extends spiderModel
{
    function getCategory()
    {
        $Category = Application::$_spider ['Category'];
        $thistimerun = isset ($Category ['Category_Run']) ? $Category ['Category_Run'] : 1;
        $collection_category_name = Application::$_spider [elements::COLLECTION_CATEGORY_NAME];
        $poolname = $this->spidername . 'Category';
        $sid = Application::$_spider ['stid'];
        $Categorylist = array();
        $Categorylist['上海'] = 'http://m.leju.com/?site=touch&ctl=js&act=fy_list&city=sh&topic_status=0&callback=jsonp1&page=';
        $Categorylist['北京'] = 'http://m.leju.com/?site=touch&ctl=js&act=fy_list&city=bj&topic_status=0&callback=jsonp1&page=';
        $mondata = array();
        foreach ($Categorylist as $name => $cid) {
            $this->pools->set($poolname, $cid);
            $mondata [] = array(
                'name' => $name,
                'cid' => $cid,
                'sid' => $sid
            );
        }
        /**
         * 写入mongodb category集合
         */
        $this->mongodb->remove($collection_category_name, array()); // 删除原始数据，保存最新的数据
        if ($mondata)
            $this->mongodb->batchinsert($collection_category_name, $mondata);
        unset($mondata);
        echo "共收集到" . count($Categorylist) . "个分类\n";
        unset($Categorylist);
        exit;
    }


    function CategroyJob()
    {
        header("Content-type: text/html; charset=utf-8");
        $name = $this->spidername . 'Category';
        $jobname = 'Category';
        $spidername = str_replace('Spider', "", $this->spidername);
        $tmp = $this->pools->get($name);
        $jobs = array_values($tmp);
        $job = $jobs[0];
        $job = 'http://m.leju.com/?site=touch&ctl=js&act=fy_list&city=sh&topic_status=0&callback=jsonp1&page=';
        $poolname = $this->spidername . 'Item';
        $Category = Application::$_spider [elements::CATEGORY];
        $run = true;
        $currpage = 1;
        $uarr = explode("?", $job);
        $p = '/city=(\w+)&topic/';
        preg_match($p, $job, $out);
        $city = isset($out[1]) && $out[1] ? $out[1] : "";
        $baseurl = $uarr[0];
        $pagesize = $Category [elements::CATEGORY_GROUP_SIZE];
        $result = array();
        $s = 1;
        $urlx = $job . '1';
        $pageHtml = $this->curlmulit->remote($urlx, null, false, Application::$_spider [elements::ITEMPAGECHARSET], Application::$_spider [elements::HTML_ZIP]);
        $pagex = str_replace(array('jsonp1(', ');'), "", $pageHtml[$urlx]);
        $jsondatax = json_decode($pagex, true);
        $totals = $jsondatax['info']['total'];
        $totalpages = ceil($totals / 10) + 1;
        do {
            if ($totalpages < $pagesize) {
                $e = $totalpages;
            } else {
                $e = $s + $pagesize;
            }
            $tmpurls = array();
            for ($ii = $s; $ii < $e; $ii++) {
                $urlxx = $job . $ii;
                $tmpurls [$urlxx] = $urlxx;
            }
            $pages = $this->curlmulit->remote($tmpurls, null, false, Application::$_spider [elements::ITEMPAGECHARSET], Application::$_spider [elements::HTML_ZIP]);
            /**
             * 能否抓去到数据检测,此代码保留
             */
            if ($s == 0 && count($pages) == 0) {
                $this->master('Item');
                $this->redis->decr($this->spidername . 'CategoryTotalCurrent');
                $this->redis->hincrby($this->spidername . $jobname . 'Current', HOSTNAME, -1);
                $this->log->errlog(array(
                    'job' => $job,
                    'Categoryurl' => $job,
                    'error' => 1,
                    'addtime' => date('Y-m-d H:i:s')
                ));
                exit ();
            }
            $preg = $Category [elements::CATEGORY_LIST_GOODS_PREG];
            $match = $Category [elements::CATEGORY_LIST_GOODS_Match];
            foreach ($pages as $rurl => $page) {
                $page = str_replace(array('jsonp1(', ');'), "", $page);
                $categorydata = json_decode($page, true);

                if (isset($categorydata['info']['entry']) && $categorydata['info']['entry']) {
                    foreach ($categorydata['info']['entry'] as $item) {
                        $item['houselink'] = 'http://esf.baidu.com/' . $city . '/detail/' . $item['id'] . '/';
                        $item['houseid'] = $item['id'];
                        $item['housetitle'] = $item['title'];
                        $item[\elements::CATEGORY_ITEM_URL] = $item['url'];
                        $item['job'] = $rurl;
                        $result[] = $item;

                        if ($item[\elements::CATEGORY_ITEM_URL])
                            $this->pools->set($poolname, $item[\elements::CATEGORY_ITEM_URL]);
                        //将category_item_url加入任务池中 2014.12.20 22:32
                        $this->mongodb->insert($this->spidername . '_category_list', $item);
                    }
                }
                unset($tmpurls[$rurl]);
                if ($categorydata['info']['is_end'] == false)
                    continue;
            }
            if ($tmpurls) {
                foreach ($tmpurls as $url)
                    $this->log->errlog(array(
                        'job' => $job,
                        'url' => $url,
                        'urltype' => 'CategoryList',
                        'error' => 1,
                        'addtime' => date('Y-m-d H:i:s')
                    ));
            }
            $sleepseconds = rand(1, 3);
            sleep($sleepseconds);
            $s = $s + $pagesize;
        } while ($s <= $totalpages);
        $this->pools->deljob($name, $job); //加入删除备份任务机制
        $this->redis->decr($this->spidername . 'CategoryTotalCurrent');
        $this->redis->hincrby($this->spidername . $jobname . 'Current', HOSTNAME, -1);
        exit;
    }
}