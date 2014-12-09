#爬虫系统开发文档
         
   
###No.1. 爬虫系统   
- pubdate: 2014-04-11 09:00
- author: living lee
- version: 1.0

##系统结构
  	app
	|-controller	存放控制器文件
	|-model		存放模型文件
	|-lib		存放自定义类库
	|-config	存放配置文件
		|--spider   爬虫配置文件夹
		|--config.php   系统配置文件
	|-system	系统核心目录
		|--core   核心文件夹
			|-- controller.php 控制器
			|-- model.php model层
		|--lib	内核扩展库
			|-- lib_CurlMulit.php 多线程抓取类
			|-- lib_download.php 下载类库
			|-- lib_HMongodb.php mongodb类库
			|-- lib_master.php master类库
			|-- lib_mysql.php mysql类库
			|-- lib_pools.php 数据池类库
			|-- lib_route.php 路由类库
			|-- lib_SRedis.php redis类库
			|-- lib_thumbnail.php 图片类库
		|--app.php   核心控制
		|--elements.php 基础参数文件
	|-index.php	入口文件

##系统运行环境
	linux系统,
	PHP 5.3+ 安装curl、redis、mongodb扩展,
	Redis 2.0+,
	Mongodb 2.0+
##配置文件说明
  	
    class elements{
	    const TYPE = 'type';  //任务类型 fulldata:全量新增 fullupdate:全量更新 incrdata:增量新增 incrupdate:增量更新
	    const CATEGORY = 'Category';
	    const Run_Type = 'normal'; //normal 分类－>分类列表－>商品详情 special query=*->全部商品列表－>商品详情
	    const CATEGORY_URL = 'Category_URL'; //全部分类url
	    const CATEGORY_MATCH_PREG = 'Category_Match_Preg';//全部分类url正则
	    const CATEGORY_MATCH_MATCH = 'Category_Match_Match';//全部分类url正则后取值
	    const CATEGORY_GROUP_SIZE = 'Category_Group_Size';//分类列表页 每次跑的页面数
	    const CATEGORY_LIST_URL = 'Category_List_URL';//分类列表页url模版
	    const CATEGORY_LIST_PREG = 'Category_List_Preg';//分类列表页url模版正则
	    const CATEGORY_LIST_MATCH= 'Category_List_Match';//分类列表页url模版正则后取值
	    const CATEGORY_LIST_GOODS_PREG = 'Category_List_Goods_Preg';//分类列表页商品url正则
	    const CATEGORY_LIST_GOODS_Match = 'Category_List_Goods_Match';//分类列表页商品url正则后取值
	    const CATEGORY_LIST_PAGES_URL = 'CATEGORY_LIST_Pages_URL';//分类列表页翻页url
	    const DB_TYPE = 'Db_Type';
	    /*商品相关参数*/
	    const ITEM = 'Item';
	    const ITEM_SOURCE_CATEGORY_ID='source_category_id';//目标网站分类id 1-2-3
	    const ITEM_SOURCE_CATEGORY_NAME='source_category_name';//目标网站分类名称 一级－二级－三级
	
	    const ITEM_CID='cid';//本地三级分类ID
	    
	    const ITEM_SOURCE_SELLER_ID='source_seller_id';//目标网站销售ID
	    const ITEM_SOURCE_SELLER_NAME='source_seller_name';//目标网站销售名称
	    const ITEM_SKUID='skuid';//商品id
	    const ITEM_TITLE='title';//商品标题
	    const ITEM_PROMOTION='promotion';//商品促销信息
	    const ITEM_SALES='sales';//商品销量
	    const ITEM_NAME='item_name';//商品名称
	    const ITEM_IMAGE_URL='image_url';//商品图片
	    
	    const ITEM_SOURCE_BRAND_ID='brand_id';//品牌id
	    const ITEM_SOURCE_BRAND_NAME='brand_name';//品牌名称
	    const ITEM_DPRICE='dprice';//现价
	    const ITEM_OPRICE='oprice';//原价
	    const ITEM_SOURCE_URL='source_url';//商品来源url
	    const ITEM_WAPURL='wapurl';//wapurl
	    const ITEM_STATUS='status';//商品状态 1:在售,有价格 ⒉:无价格，有商品2:无价格无商品
	    const ITEM_CHARACTERS='characters';//商品特征
	    const ITEM_DATA_SOURCE='data_source';//数据来源 1:采集 2:api 3:导入
	    const ITEM_DESCRIPTION='description';//商品描述 
	    const ITEM_ISBN='isbn';//isbn
	    
	    const ITEM_BARCODE='barcode';//Barcode
	    const CREATE_TIME='create_time';//新增时间
	    const UPDATE_TIME='update_time';//更新时间
	   
	    
	    /* basic items of the particular market */
	    const MANAGER = 'manager';//脚本负责人
	    const BASE_URL = 'baseurl';//网站地址
	    const STID = 'stid';//网站编号
	
	    /* page number as well other page patterns */
	    const CHARSET = 'charset';//网站编码
	}
### 数据字典
![](images/datadict.jpg)

### 系统架构图
![](images/spiderstruct.jpg)

##爬虫系统的四种工作模式
* fulldata 全量新增

	针对新接入的网站采用全新的插入方式。

* updatedata 全量更新
	可定制根据某写字段进行更新，比如通过price_url可以更新价格信息，通过source_url可以更新部分商品信息
* incrupdate 局部新增
	针对已经接入的网站进行少量的商品数据添加。比如每次查询该分类下面的前10页，采用单个商品插入的方式进行新增。
* incrupdate 局部更新
	针对接入网站的某些分类进行数据的更新。比如有的网站数据分类下面的商品进行的调整，可以单独拧出了跑该分类。

##以苏宁为例说明整个系统的执行过程
###编写suningSpider.php配置文件,文件的命名规则：取wcc\_online\_data库中的source集合数据中对应的domain，如下图
![](images/suning.jpg)

		$siteconfig = array (
		elements::TYPE => 'fulldata',
		elements::NAME =>'苏宁', 
		elements::CRAWL => array (
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:27.0) Gecko/20100101 Firefox/27.0' 
		),
		elements::DB => array (
				elements::DB_TYPE =>'Mongdb',
				elements::HOST => 'localhost',
				elements::PORT => '27017',
				elements::DATABASE => 'wcc_online_data',
		),
		elements::CATEGORY=>array(
				elements::CATEGORY_URL=>'http://www.suning.com/emall/pgv_10052_10051_1_.html',//全部分类url
				elements::CATEGORY_MATCH_PREG=>'/class="searchCity"\sid="(\d+)"\s+href="http:\/\/search\.suning\.com\/emall\/strd.do/iUs',//全部分类页面正则表达式
				elements::CATEGORY_MATCH_MATCH=>1,//全部分类页面正则后取第几个值
				elements::CATEGORY_GROUP_SIZE=>10,//分类列表页分页处理后，每次跑几个页面
				elements::CATEGORY_LIST_URL=>'http://search.suning.com/emall/strd.do?ci=#job&cityId=9264',//分类列表页模版，#job为分类id
				elements::CATEGORY_LIST_PAGES_URL=>'http://search.suning.com/emall/showProductList.do?ci=#job&cityId=9264&pg=03&cp=#i&il=0&si=5&st=14&iy=0&n=1',//分类列表页分页模版，#job为分类id，部分网站分页不一样，需要特殊处理
				elements::CATEGORY_LIST_PREG=>'/<i id="pageTotal">(\d+)<\/i>/iUs',//获取分类列表页改分类的总页数 正则表达式
				elements::CATEGORY_LIST_MATCH=>1,获取分类列表页改分类的总页数 正则取值
				elements::CATEGORY_LIST_GOODS_PREG=>'/http:\/\/product\.suning\.com\/(.*)\.html/iUs',//获取分类列表页中商品url的正则表达式
				elements::CATEGORY_LIST_GOODS_Match=>0//获取分类列表页中商品url的正则后取值
				/**
				*	$preg:正则表达式； 数据来源：suningSpider.php中的*	CATEGORY::CATEGORY_URL=>key（Category_Url）
				*	$content:内容； $tcurl->remote(CATEGORY::CATEGORY_URL)
				*	$match 获取的值； 数据来源：suningSpider.php中的*	CATEGORY::CATEGORY_MATCH_MATCH=>key（Category_Match_Match）
				*	example: preg_match_all($preg,$content,$match)
				*	
				*/
				),
		elements::ITEM=>array(
				/**
				* 商品详情的正则获取配置
				*/ 
				elements::ITEM_TITLE=>'/class="wb"\s+title="(.*)"/iUs||1||1',
				
				elements::ITEM_SOURCE_CATEGORY_ID=>'/categoryId="(\d+)"/iUs||1||1',
 				elements::ITEM_SKUID=>'/"partNumber":"(\d+)"/iUs||1||1',
				elements::ITEM_IMAGE_URL=>'/<li><img src="(.*)" src3="(.*)" alt=""><\/li>/iUs||2||{"1":"mid","2":"big"}',

		),
		elements::BASE_URL => 'http://www.jd.com/book/booksort.aspx',
		elements::STID => 6,//网站编码，来自source集合中的设定
		elements::DATASOURCE => 'API_C',
		elements::CHARSET => '/charset="?(.*?)"/i',
		
		);



### 系统运行过程图

![](images/systemrun.jpg)
   
### 数据池管理图

![](images/datapool.jpg)

## 基础说明
####爬虫模块
数据抓取分成两种类型，利用搜索引擎的漏洞直接得到商品全量列表形式的常规抓取与需要走首先捕捉到分类继而捕捉到商品列表的特殊形式抓取。
常规抓取：调用curl采集模块，抓取网站全量数据，并进行批量整理；
特殊抓取：获取分类首页之后，将分类列表页url拆分成任务放入任务池，继而调用分布式任务调度模块来或许商品详情url并将相关url存入任务池。
由以上二者所得到的商品数据通过调用分布式任务系统来提取商品数据进入数据转换模块的操作。
抓取模块使用php的curl扩展来实现，并借助curl_multi类来实现批量抓取。前端传入url数组，抓取模块捕捉到之后进行参数选项的添加，并设置超时时间来控制url抓取时间上的控制。

关键代码与执行流程如下所示:

	$curl = $text = array();
	$handle = curl_multi_init();
	foreach($urls as $k => $v) {   //循环传入url
	    $nurl[$k]= $v;
	    $curl[$k] = curl_init($nurl[$k]);   //初始化批量curl链接
	    $ip = $this->Rand_IP();             //动态IP生成
	    curl_setopt($curl[$k], CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));
	    curl_setopt($curl[$k], CURLOPT_HEADER, $header); 
	    curl_setopt($curl[$k], CURLOPT_RETURNTRANSFER, true); 
	    curl_setopt($curl[$k], CURLOPT_FOLLOWLOCATION, true);
	     curl_setopt($curl[$k], CURLOPT_NOBODY, false);
	    if($reffer)
	        curl_setopt($curl[$k], CURLOPT_REFERER, $reffer);//来路地址
	    curl_setopt($curl[$k], CURLOPT_USERAGENT, $user_agent);
	    curl_setopt($curl[$k],CURLOPT_TIMEOUT,10);//过期时间
	    curl_multi_add_handle ($handle, $curl[$k]);   //添加处理句柄
	}
	$active = null;
	do {
	    $mrc = curl_multi_exec($handle, $active);    //执行批量采集
	} while ($mrc == CURLM_CALL_MULTI_PERFORM);

	while ($active && $mrc == CURLM_OK) {
	    if (curl_multi_select($handle) != -1) {
	        do {
	            $mrc = curl_multi_exec($handle, $active);
	        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
	    }
	}
	foreach ($curl as $k => $v) {
	    if (curl_error($curl[$k]) == "") {
	    $text[$k] = (string) curl_multi_getcontent($curl[$k]);
	    }
	    curl_multi_remove_handle($handle, $curl[$k]);   //操作完毕后移除curl句柄
	    curl_close($curl[$k]);
	}
	curl_multi_close($handle);    //关闭套接字
	return $text;

**根据配置里面正则和需要获取的内容，匹配出需要的数据

		function itemBaseJob() {
			$poolname = $this->spidername . 'Item';
			$Category = Application::$_spider ['Category'];
			$urls = $this->pools->get ( $poolname, 2 );
			$pages = $this->curlmulit->remote ( $urls, null, false );
			$fetchitems = array ();
			foreach ( $pages as $srouceurl => $page ) {
				$items = array ();
				$spideritems = Application::$_spider ['Item'];
				$items ['source_url'] = $srouceurl;
				$items ['pagesource'] = $page;
				$items ['stid'] = Application::$_spider ['stid'];
				foreach ( $spideritems as $key => $val ) {
					$arr = explode ( "||", $val );
					$preg = $arr [0];
					$op = $arr [1];
					if ($op == 1) {
						$match = $arr [2];
						preg_match ( $preg, $page, $match_out );
						$v = $match_out [$match];
						$items [$key] = $v;
					} else {
						$mutil = json_decode ( $arr [2], true );
						
						preg_match_all ( $preg, $page, $match_out );
						$a_tmp = array ();
						foreach ( $mutil as $mnum => $vname ) {
							$a_tmp [$vname] = $match_out [$mnum];
						}
						$items [$key] = $a_tmp;
						$v = $match_out [$match];
						$items [$key] = $v;
					}
				}
				$fetchitems [] = $items;
			}
			return $fetchitems;
		}
		...
##针对基础函数无法获取的数据，可以通过扩展进行再次获取

		 /**
	     * 获取分类数据
	     * @param  $category
	     * @return multitype:
	     */
		function itemjob()
		{
			$data = $this->itemBasejob();
			$items = array();
			foreach($data as $item)
			{
				$preg = '/{"category1":"(.*)"uuid"/iUs';
				preg_match( $preg, $item['pagesource'], $match_out );
				$str = str_replace(',"uuid"', '}', $match_out[0]);
				$cate = json_decode($str,true);
				$item['source_category_id'] = $cate['category1'].'-'.$cate['category2'].'-'.$cate['category3'];
				$item['source_category_name'] = $cate['categoryName1'].'-'.$cate['categoryName2'].'-'.$cate['categoryName3'];
				...
				$items[] = $item;
			}
			unset($item['pagesource']);
			$this->mongodb->batchinsert($spidercollectionname,$item);
		}



##分布式任务调度模块
　　
分布式任务调度模块分为数据池管理和任务调度管理。

数据池管理：

数据池为一个队列体系，队列中包含多个待处理的tasks和jobs。Tasks为入队的商品url和分类url任务，jobs执行将分类和商品url的任务进行处理。数据池不断更新，新任务新增进任务池，队列长度增加；同时依序从数据池中取出任务来进行处理，队列自动减短。

任务调度管理：

负责管理任务的中枢称为Master。Master首先检查任务是否完成，即检查任务池是否为空，若为空则终止Master的任务调度；否则Master从任务池中获取任务，并根据任务级别创建新任务等，并逐个启动Jobs来处理各个任务，任务处理完毕后将数据反馈给调用者，进入数据转换环节。
数据转换完毕后，数据进入数据仓库供后续调用和操作。


##数据库模块
数据库模块主要承担数据入库职责，主要使用MongoDB作为数据存储，并使用Redis配合作为分布式的队列机制。

MongoDB存储部分：主要封装为HMongod类，完成对数据库的CURD操作与异常处理机制。性能方面优先使用MongoDB的batchInsert支持来实现批量插入。

Redis队列方面：主要封装队列的key-value删改及入队出队机制，利用Redis内存数据库的优势及对分布式的支持来保障批量抓取的高效性。

##预警模块
本模块致力于处理抓取系统中可能出现的各种异常情况，例如网络中断，机器宕机、抓取脚本停顿等情况，以及断点续传、保护现场及对于MongoDB的mapReduce的利用等操作，将在后续进行完善。

