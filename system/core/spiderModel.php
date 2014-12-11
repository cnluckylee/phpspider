<?php
/**
 *
 * @copyright   Copyright(c) 2014
 * @author      cnlucky_lee <cnlucky_lee@gmail.com>
 * @version     1.0
 */
class spiderModel extends Model {
	protected $itemjob = false;
	
	/**
	 * 获取分类数据
	 */
	function getCategory() {
        header("Content-type: text/html; charset=utf-8");
		$Category = Application::$_spider ['Category'];
		$thistimerun = isset ( $Category ['Category_Run'] ) ? $Category ['Category_Run'] : 1;
		$collection_category_name = Application::$_spider ['collection_category_name'];
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
            $tmp = $this->curlmulit->remote( $Category_URL , null, false, Application::$_spider [ elements::ITEMPAGECHARSET]);
            $page = $tmp[$Category_URL];
            $preg = $Category [elements::CATEGORY_MATCH_PREG];
            $matchnum = $Category [elements::CATEGORY_MATCH_MATCH];
            $Categorytmp = array();
            if(strtolower($Category[elements::CATEGORY_MATCHING]) == 'xpath')
            {
                $dom = new DOMDocument();
                @$dom->loadHTML($page);
                $xpath = new DOMXPath($dom);
                $result = $xpath->query($preg);
                for($i = 0; $i < ($result->length); $i++) {
                    $event = $result->item($i);
                    if(in_array('text',$matchnum))
                    {
                        $cid = $event->nodeValue;
                    }
                    foreach($event->attributes as $k=>$v)
                    {
                        if(in_array($v->name,$matchnum) && $cid){
                            $Categorytmp[$v->nodeValue] = $cid;
                        }
                    }

                    if((isset($Categorytmp[$i]['cid']) && !$Categorytmp[$i]['cid']) || !isset($Categorytmp[$i]['cid']))
                         unset($Categorytmp[$i]);
                }
                $Categorylist = $Categorytmp;
            }else{
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
            }

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
	/**
	 * 分类列表任务调度
	 *
	 * @return Ambigous <number, unknown>
	 */
	function master($jobname = 'Category') {
		$name = $this->spidername . $jobname;
		$totalvalue = 0;
		do {
			$totalvalue = $this->pools->size ( $name );
			$jobs = $this->redis->get ( $this->spidername . $jobname . 'Current' ); // 当前运行数
			echo $this->spidername." Jobname:" . $jobname . "  totalvalue:" . $totalvalue . " jobs:" . $jobs . " maxjobs:" . $this->maxjobs . "\n";
			if ($totalvalue > 0) {
				$runs = $this->maxjobs;
				// 刚起步程序
				if (! $this->redis->exists ( $this->spidername . $jobname . 'Current' )) {
					if ($totalvalue < $this->maxjobs)
						$runs = $totalvalue;
					$cmd = "./startworker " . $this->spidername . '  ' . $jobname . "job " . $runs;
					$this->redis->incr ( $this->spidername . $jobname . 'Current', $runs );
					$out = popen ( $cmd, "r" );
					pclose ( $out );
				} else if ($jobs >= $this->maxjobs) 				// 当前运行数大于最大运行数 自动暂停3秒
				{
					sleep ( 1 );
				} else if ($jobs > 0 && $jobs < $this->maxjobs) 				// 当前运行数不足最大运行数 加入新的任务
				{
					if ($totalvalue < $this->maxjobs)
						$runs = $totalvalue;
					else
						$runs = $this->maxjobs - $jobs;
					$cmd = "./startworker " . $this->spidername . '  ' . $jobname . "job " . $runs; // $cmd = "./startworker " . $spidername . " categoryjob " . $runs
					echo "cmd:" . $cmd . "\n";
					$out = popen ( $cmd, "r" );
					pclose ( $out );
					$this->redis->incr ( $this->spidername . $jobname . 'Current', $runs );
				} else if ($jobs <= 0) {
					$runs = $this->maxjobs;
					if ($totalvalue < $this->maxjobs)
						$runs = $totalvalue;
					$cmd = "./startworker " . $this->spidername . '  ' . $jobname . "job " . $runs;
					$this->redis->incr ( $this->spidername . $jobname . 'Current', $runs );
					$out = popen ( $cmd, "r" );
					pclose ( $out );
				}
				$this->log->runlog ( array (
						'start' => 0,
						'add' => $runs,
						'addtime' => date ( 'Y-m-d H:i:s' ),
						'onstart' => 1 
				) );
			} else {
				$this->spiderrun = false;
				if ($jobname == 'Category') {
					$this->autostartitemmaster ();
					exit ( "Category stacks over\n" );
				} else {
					// 商品跑完了，等于全部跑完了,收拾战场
					$this->redis->delete ( $this->spidername . 'ItemJobRun' );
				}
			}
		} while ( $this->spiderrun );
		$this->redis->delete ( $this->spidername . 'CategoryCurrent' );
		$this->redis->delete ( $this->spidername . 'ItemCurrent' );
		$this->redis->delete ( $this->spidername . 'Item' );
		$this->redis->delete ( $this->spidername . 'ItemJobRun' );
		$this->mongodb->remove($this->spidername.'_err_log');
		$this->mongodb->remove($this->spidername.'_warning_log');
		$this->mongodb->remove($this->spidername.'_msg_log');
		exit ( "stack all over\n" );
	}
	function CategroyJob() {
        header("Content-type: text/html; charset=utf-8");
		$name = $this->spidername . 'Category';
		$spidername = str_replace ( 'Spider', "", $this->spidername );
//		$job = $this->pools->get ( $name );
        $job = '爽肤水';
		$poolname = $this->spidername . 'Item';
		$Category = Application::$_spider ['Category'];
		
//		$Categoryurl = str_replace ( "#job", $job, $Category ['Category_List_URL'] );
$Categoryurl = 'http://search.jumei.com/?filter=0-11-1&search=爽肤水';
		// 首先获取下该分类下面的总页数
		$pageHtml = $this->curlmulit->remote ( $Categoryurl, null, false );
		if (! $pageHtml) {
			$this->autostartitemmaster ();
			$this->redis->decr ( $this->spidername . 'CategoryCurrent' );
			$this->log->errlog ( array (
					'job' => $job,
					'Categoryurl' => $Categoryurl,
					'error' => 2,
					'addtime' => date ( 'Y-m-d H:i:s' ) 
			) );
			exit ();
		}
		$preg_pagetotals = $Category [elements::CATEGORY_LIST_PREG];
        if(strtolower($Category[elements::CATEGORY_MATCHING]) == 'xpath')
        {
            $totalpages = $this->curlmulit->getRegexpInfo($preg_pagetotals,$pageHtml [$Categoryurl],$Category [elements::CATEGORY_LIST_MATCH]);
        }else{
            preg_match ( $preg_pagetotals, $pageHtml [0], $match_pagetotals );
            $totalpages = $match_pagetotals ? $match_pagetotals [$Category ['Category_List_Match']] : 0;
        }
        $Category_collectionname = $this->spidername . 'Category';
        $this->mongodb->update ( $Category_collectionname,
                                array ('cid' => $job),
                                array('$set'=>array('totalcount'=>$totalpages)),
                                 array("upsert"=>1,"multiple"=>true));

		$totalpages = intval ( $totalpages );
		$s = isset ( $Category [elements::CATEGORY_PAGE_START] ) ? $Category[elements::CATEGORY_PAGE_START] : 0;
		$pagesize = $Category [elements::CATEGORY_GROUP_SIZE];
		if ($totalpages > 0) {
			$randtimes = ceil ( $totalpages / $pagesize );
			// 循环获取商品的url地址
			do {
				if ($totalpages < $pagesize) {
					$e = $totalpages;
				} else {
					$e = $s + $pagesize;
				}
				$tmpurls = array ();
				for($i = $s; $i < $e; $i ++) {
					$url = $Category [elements::CATEGORY_LIST_PAGES_URL];
					$url = str_replace ( '#job', $job, $url );
					$url = str_replace ( '#i', $i, $url );
					$tmpurls [$url] = $url;
				}

				$pages = $this->curlmulit->remote ( $tmpurls, null, false );

				/**
				 * 能否抓去到数据检测,此代码保留
				 */
				if ($s == 0 && count ( $pages ) == 0) {
					$this->master ( 'Item' );
					$this->redis->decr ( $this->spidername . 'CategoryCurrent' );
					$this->log->errlog ( array (
							'job' => $job,
							'Categoryurl' => $Categoryurl,
							'error' => 1,
							'addtime' => date ( 'Y-m-d H:i:s' ) 
					) );
					exit ();
				}
				$preg = $Category [elements::CATEGORY_LIST_GOODS_PREG];
				$match = $Category [elements::CATEGORY_LIST_GOODS_Match];
				foreach ( $pages as $rurl => $page ) {
                    if(strtolower($Category[elements::CATEGORY_MATCHING]) == 'xpath')
                    {
                        $item_urls = $this->curlmulit->getRegexpInfo2($preg,$page);
                    }else{
                        preg_match_all ( $preg, $page, $match_out );
                        $item_urls = isset ( $match_out [$match] ) ? $match_out [$match] : "";
                    }
                    $item_urls = array_unique ( $item_urls );
					// 加入itemjobs
					foreach ( $item_urls as $url ) {
						$this->pools->set ( $poolname, $url );
					}
                    //加入错误日志
                    unset($tmpurls[$rurl]);
				}
				$s = $s + $pagesize;
                if($tmpurls)
                {
                    foreach($tmpurls as $url)
                    $this->log->errlog ( array (
                        'job' => $job,
                        'url' => $url,
                        'urltype' =>'CategoryList',
                        'error' => 1,
                        'addtime' => date ( 'Y-m-d H:i:s' )
                    ) );
                }

			} while ( $s <= $totalpages );
		}
        exit;
		$jobs1 = $this->redis->get ( $this->spidername . 'CategoryCurrent' );
		$this->redis->decr ( $this->spidername . 'CategoryCurrent' );
		$jobs2 = $this->redis->get ( $this->spidername . 'CategoryCurrent' );
		$this->log->msglog ( array (
				'job' => $job,
				'runjobs1' => $jobs1,
				'runjobs2' => $jobs2,
				'addtime' => date ( 'Y-m-d H:i:s' ) 
		) );
		$this->autostartitemmaster ();
		exit ();
	}
	function autostartitemmaster($jobname = 'Item') {
		if (! $this->redis->exists ( $this->spidername . $jobname . 'JobRun' )) {
			$this->redis->set ( $this->spidername . $jobname . 'JobRun', 1 );
			$ljobname = lcfirst ( $jobname );
			exec ( "php -f index.php " . $this->spidername . " " . $ljobname . "master " . $this->maxjobs );
		}
	}
	function itemjob() {
		$poolname = $this->spidername . 'Item';
		$Category = Application::$_spider ['Category'];
		$collection_item_name = Application::$_spider ['collection_item_name'];
		if(isset($_GET['debug']) && $_GET['debug']=='itemjob')
		{
				$urls = isset($_GET['url'])?trim($_GET['url']):"";
		}else			
			$urls = $this->pools->get ( $poolname, $Category ['Category_Group_Size'] );		
		$pages = $this->curlmulit->remote ( $urls, null, false, Application::$_spider ['item_page_charset'] );
// 		$fetchitems = array ();
		$Productmodel = $this->spidername . 'ProductModel';
		foreach ( $pages as $srouceurl => $page ) {
			$spidermodel = new $Productmodel ( $this->spidername, $srouceurl, $page, Application::$_spider );
			$spiderdata = $spidermodel->exportToArray ();
			if($spiderdata['title'])
			{
// 				$fetchitems [] = $spiderdata;
				$this->mongodb->update('wcc_online_data', array('skuid'=>$spiderdata['skuid'],'stid'=>$spiderdata['stid']),$spiderdata,array("upsert"=>1));
			}
			if(isset($_GET['debug']) && $_GET['debug']=='itemjob')
			{
				print_r($spiderdata);exit;
			}	
		}		
		$this->redis->decr ( $this->spidername . 'ItemCurrent' );
		exit ();
	}
	/**
	 * 全量更新
	 */
	function updatefull() {
		$spiderconfig = Application::$_spider;
		$updateconfig = isset ( $spiderconfig ['updatedata'] ) ? $spiderconfig ['updatedata'] : "";
		if (! $updateconfig) {
			exit ( $this->spidername . "'s updateconfig not find" );
		}
		// 清理现场
		$this->redis->delete ( $this->spidername . 'UpdateJobRun' );
		$this->redis->delete ( $this->spidername . 'Update' );
		$this->redis->delete ( $this->spidername . 'UpdateCurrent' );
		/*
		 * 数据入池
		 */
		$collectionname = 'wcc_' . $this->spidername . '_items';
		$limit = 1000;
		$totalitems = $this->mongodb->count ( $collectionname );
		$s = 0;
		$poolname = $this->spidername . 'Update';
		do {
			$mondata = $this->mongodb->find ( $collectionname, array (), array (
					"start" => $s,
					"limit" => $limit
			) );
			foreach ( $mondata as $item ) {
				// $arr = array('price_url'=>$item['price_url'],'source_url'=>$item['source_url'],'id'=>$item['_id']);
				$str = serialize ( $item );
				$this->pools->set ( $poolname, $str );
			}
			$s += $limit;
			echo 'has load '.$s."\n";
		} while ( $s <= $totalitems );
		$this->autostartitemmaster ( 'Update' );
		exit ('Update Stack over');
	}
	
	/**
	 * updatejob
	 */
	function updatejob() {
		$poolname = $this->spidername . 'Update';
		$collectionname = 'wcc_' . $this->spidername . '_items';
		$spiderconfig = Application::$_spider;
		$Category = $spiderconfig ['Category'];
		$updateconfig = isset ( $spiderconfig ['updatedata'] ) ? $spiderconfig ['updatedata'] : "";
		if (! $updateconfig) {
			exit ( $this->spidername . "'s updateconfig not find" );
		}
		$strs = $this->pools->get ( $poolname, $Category ['Category_Group_Size'] );		
		$priceurls = $sourceurls = array ();
		$Productmodel = $this->spidername . 'ProductModel';
		foreach ( $strs as $str ) {
			$item = unserialize ( $str );
			if (in_array ( 'dprice', $updateconfig )) {
				// 更新价格
				$urls = $item ['price_url'];
			} else {
				$urls = $item ['source_url'];
			}
			$pages = $this->curlmulit->remote ( $urls, null, false, Application::$_spider ['item_page_charset'] );
			if ($pages) {
				foreach ( $pages as $srouceurl => $page ) {
					$spidermodel = new $Productmodel ( $this->spidername, $srouceurl, $page, Application::$_spider );
					$spiderdata = $spidermodel->exportToArray ( $updateconfig, $item );
					$this->mongodb->update ( $collectionname, array ('_id' => $item ['_id']), $spiderdata,array("upsert"=>1));
				}
			}
		}
		$this->redis->decr ( $this->spidername . 'UpdateCurrent' );
		exit;
	}
}
