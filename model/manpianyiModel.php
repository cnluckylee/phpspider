<?php
/**
 * 
 * @author cnluckylee@gmail.com
 *
 */
class manpianyiModel extends spiderModel {
	/**
	 * 获取分类数据
	 */
	function getCategory() {
		$Category = Application::$_spider ['Category'];
		$thistimerun = isset ( $Category ['Category_Run'] ) ? $Category ['Category_Run'] : 1;
		$collection_category_name = Application::$_spider ['collection_category_name'];
		$poolname = $this->spidername . 'Category';
		// 清理Category现场
		$this->pools->del ( $poolname );
		$this->redis->delete ( $this->spidername . 'CategoryCurrent' );
		$this->redis->delete ( $this->spidername . 'ItemCurrent' );
		$this->redis->delete ( $this->spidername . 'Item' );
		$this->redis->delete ( $this->spidername . 'ItemJobRun' );
		// 判断本次是否重新抓取分类数据
		if ($thistimerun) {
			$sid = Application::$_spider ['stid'];
			
			/**
			 * 固定分类
			 */
			$Categorylist = array();
			$Categorylist['限时折扣'] = 'zhekou';
			$Categorylist['品牌秒杀'] = 'brand';
			$Categorylist['9块9包邮'] = 'baoyou';
			$Categorylist['其他'] = 'qita';
			$Categorylist['女装'] = 'nvzhuang';
			$Categorylist['男装'] = 'nanzhuang';
			$Categorylist['家居'] = 'jiaju';
			$Categorylist['美妆'] = 'huazhuangpin';
			$Categorylist['鞋包'] = 'xiebao';
			$Categorylist['美食'] = 'meishi';
			$Categorylist['配饰'] = 'peishi';
			$Categorylist['母婴'] = 'muying';
			$Categorylist['数码'] = 'shuma';
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
	}
	
	
	/**
	 * 获取分类job
	 */
	function CategroyJob() {
		
		$name = $this->spidername . 'Category';
		$spidername = str_replace ( 'Spider', "", $this->spidername );
		$job = $this->pools->get ( $name );
		$poolname = $this->spidername . 'Item';
		$Category = Application::$_spider ['Category'];
		$Categoryurl = str_replace ( "#job", $job, $Category ['Category_List_URL'] );
// 		$Categoryurl = 'http://www.manpianyi.com/shuma.html?url=list';
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
		$preg_pagetotals = $Category ['Category_List_Preg'];
		preg_match_all ( $preg_pagetotals, $pageHtml [0], $match_pagetotals );
		
		$totalpages = isset($match_pagetotals[$Category ['Category_List_Match']])  && $match_pagetotals[$Category ['Category_List_Match']] >0 ? $match_pagetotals [$Category ['Category_List_Match']] : 0;
		
		$totalpages = is_int($totalpages) && $totalpages >0 ? $totalpages : 1;
		
		$totalpages = intval ( $totalpages ) + 1;
		$s = isset ( $Category ['Category_Page_Start'] ) ? $Category ['Category_Page_Start'] : 0;
		$pagesize = $this->runpages;

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
					$url = $Category ['CATEGORY_LIST_Pages_URL'];
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
				$preg = $Category ['Category_List_Goods_Preg'];
				$match = $Category ['Category_List_Goods_Match'];
				foreach ( $pages as $rurl => $page ) {
					preg_match_all ( $preg, $page, $match_out );
					$item_urls = isset ( $match_out [$match] ) ? $match_out [$match] : "";
					
					$item_urls = array_unique ( $item_urls );
					
					foreach($item_urls as $obj)
					{
						$mongodata = array('auctionId'=>$obj,'category'=>$job,'addtime'=>time(),'updtime'=>time());
						$this->mongodb->update('TBKAuctionIds',array('auctionId'=>$obj,'category'=>$job),$mongodata,array("upsert"=>1));
					}
				}
				$s = $s + $pagesize;
			} while ( $s <= $totalpages );
		}
		$jobs1 = $this->redis->get ( $this->spidername . 'CategoryCurrent' );
		$this->redis->decr ( $this->spidername . 'CategoryCurrent' );
		$jobs2 = $this->redis->get ( $this->spidername . 'CategoryCurrent' );
		$this->log->msglog ( array (
				'job' => $job,
				'runjobs1' => $jobs1,
				'runjobs2' => $jobs2,
				'addtime' => date ( 'Y-m-d H:i:s' )
		) );

		exit ();
	}
	
	
}






















