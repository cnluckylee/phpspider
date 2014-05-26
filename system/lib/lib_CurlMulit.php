<?php

/**
 * 模拟多线程采集
 *  demo:
 *  $text = remote(array('http://www.imfeng.com/','http://www.aligaduo.com/'));
 *  print_r($text);
 */
class CurlMulit {

    function remote($tmpurls, $reffer = null, $header = true,$charset=null) {
        $urls = array();
        if ($tmpurls && !is_array($tmpurls)) {
            $urls[] = $tmpurls;
        } else if (is_array($tmpurls)) {
            $urls = $tmpurls;
        } else {
            return false;
        }

        $user_agent = "Mozilla/5.0 (compatible; Baiduspider/2.0);+http://www.baidu.com/search/spider.html"; //来路

        $curl = $text = array();
        $handle = curl_multi_init();
        foreach ($urls as $k => $v) {
//         $nurl[$k]= preg_replace('~([^:\/\.]+)~ei', "rawurlencode('\\1')", $v);
            $nurl[$k] = $v;
            $curl[$k] = curl_init($nurl[$k]);
            $ip = $this->Rand_IP();
            curl_setopt($curl[$k], CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip, 'CLIENT-IP:' . $ip));

            curl_setopt($curl[$k], CURLOPT_HEADER, $header);
            curl_setopt($curl[$k], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl[$k], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl[$k], CURLOPT_NOBODY, false);
            if ($reffer)
                curl_setopt($curl[$k], CURLOPT_REFERER, $reffer); //来路地址
            curl_setopt($curl[$k], CURLOPT_USERAGENT, $user_agent);
            curl_setopt($curl[$k], CURLOPT_TIMEOUT, 10); //过期时间
            curl_multi_add_handle($handle, $curl[$k]);
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($handle, $active);
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
            	if($charset){
            		$texttmp = (string) curl_multi_getcontent($curl[$k]);
            		$text[$k] = mb_convert_encoding($texttmp, "utf-8",$charset);
            	}else
            		$text[$k] = (string) curl_multi_getcontent($curl[$k]);
                
            }
            curl_multi_remove_handle($handle, $curl[$k]);
            curl_close($curl[$k]);
        }
        curl_multi_close($handle);
        return $text;
    }

    //动态ip
    protected function Rand_IP() {
        $ip2id = round(rand(600000, 2550000) / 10000);
        $ip3id = round(rand(600000, 2550000) / 10000);
        $ip4id = round(rand(600000, 2550000) / 10000);
        $arr_1 = array("218", "218", "66", "66", "218", "218", "60", "60", "202", "204", "66", "66", "66", "59", "61", "60", "222", "221", "66", "59", "60", "60", "66", "218", "218", "62", "63", "64", "66", "66", "122", "211");
        $randarr = mt_rand(0, count($arr_1) - 1);
        $ip1id = $arr_1[$randarr];
        return $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
    }

}
