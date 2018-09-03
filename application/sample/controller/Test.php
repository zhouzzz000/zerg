<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/4/15
 * Time: 20:41
 */

namespace app\sample\controller;
require 'curl.php';
class Test
{
    public function getInfo1()
    {
        $cl = new curl();
        error_reporting( E_ALL&~E_NOTICE );
        $res = [];
        $url = 'http://210.34.84.1/default2.aspx';
        $result = $cl->curl_request($url);
        $parttern = '/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is';
        preg_match_all($parttern,$result,$matches);
        $res[0] = $matches[1][0];
        $parttern = '/<input type="hidden" name="__VIEWSTATEGENERATOR" value="(.*?)" \/>/is';
        preg_match_all($parttern,$result,$matches);
        $res[1] = $matches[1][0];
        preg_match('/Location: \/\((.*)\)/', $result,$matches);
        $res[2] = $matches[1][0];
        var_dump($res);
        //return $res;
    }
    public function login($value='')
    {
        $cl = new curl();
        $res = $this->getInfo1();
        $url = 'http://jwgl.fafu.edu.cn/(ltxrg4j5l5ocra45okz51045)/default2.aspx';
        $post['__VIEWSTATE'] = $res[0];
        $post['__VIEWSTATEGENERATOR'] = $res[1];
        $post['txtUserName'] = '3166016076';
        $post['Textbox1'] = '';
        $post['TextBox2'] = 'zhouzhiwen00';
        $post['txtSecretCode'] = '';
        $post['RadioButtonList1'] = iconv('utf-8','gb2312','学生');
        $post['Button1'] = '';//iconv('utf-8','gb2312','登陆');
        $post['lbLanguage'] = '';
        $post['hidPdrs']='';
        $post['hidsc'] = '';
        $post1['xh'] = '3166016076';
        $sec = $cl->curl_request('http://jwgl.fafu.edu.cn/(ltxrg4j5l5ocra45okz51045)/default2.aspx/??=\'\'');
        echo $sec;
        $result = $cl->curl_request($url,$post,'',1);
        $result1 = $cl->curl_request($url,$post1,'',1);
        print_r($result);
        echo $result['cookie'];
        echo $result1;
    }
}