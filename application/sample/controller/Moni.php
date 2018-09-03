<?php

namespace app\sample\controller;

use think\Controller;
use think\Exception;

class Moni extends Controller
{
    public $sid = '3166016076';
    public $password = 'zhouzhiwen00';
    public $identity = '学生';
    public $temp;
    /*
     *此处的作用是：
     *获得url中的随即参数和__VIEWSTATE
     *
     */
    function getView()
    {
        $url = 'http://jwgl.fafu.edu.cn/default2.aspx';
        $result = $this->curl_request($url);
        if (empty($result)) {
            return array(
                'status' => "0",
                'message' => "模拟登陆失败，网址可能以改变",
            );
        }
        preg_match('/Location: \/\((.*)\)/', $result, $temp);
        $pattern = '/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is';
        preg_match_all($pattern, $result, $matches);
        $res[0] = $matches[1][0];
        $res[1] = $temp[1];
        $parttern = '/<input type="hidden" name="__VIEWSTATEGENERATOR" value="(.*?)" \/>/is';
        preg_match_all($parttern, $result, $matches);
        $res[2] = $matches[1][0];
        if (empty($res)) {
            return array(
                'status' => "0",
                'message' => "获取随即参数或_VIEWSTATE失败",
            );
        }
        return array(
            'status' => "1",
            'message' => $res,
        );
    }

    //参数1：访问的URL，参数2：post数据(不填则为GET)，3是否需要加referer url
    function curl_request($url, $post = '', $referer = '')
    {//$cookie='', $returnCookie=0,
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://jwgl.fafu.edu.cn/default2.aspx");
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }

        if ($referer) {
            curl_setopt($curl, CURLOPT_REFERER, $referer);
        }
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    /*
     *此处的作用是：
     *模拟登陆校网，如果返回学生姓名，则登陆成功
     *
     */
    function login()
    {
        $this->temp[0] = $_POST['serectCode'];
        $this->temp[1] = $_POST['data1'];
        $this->temp[2] = $_POST['data2'];
        $this->temp[3] = $_POST['data3'];
        var_dump($this->temp);
        $url = 'http://jwgl.fafu.edu.cn/('.$this->temp[3].')/default2.aspx';//
        $post['__VIEWSTATE'] = $this->temp[1];
        $post['__VIEWSTATEGENERATOR'] = $this->temp[2];
        $post['txtUserName'] = $this->sid;
        $post['TextBox2'] = $this->password;
        $post['Textbox1'] = '';
        $post['txtSecretCode'] = $this->temp[0];
        $post['lbLanguage'] = '';
        $post['hidPdrs'] = '';
        $post['hidsc'] = '';
        $post['RadioButtonList1'] = iconv('utf-8', 'gb2312', $this->identity);
        $post['Button1'] = '';//iconv('utf-8', 'gb2312', '登录');
        $result = $this->curl_request($url, $post);
        if (empty($result)) {
            return array(
                'status' => "0",
                'message' => "模拟登陆失败",
            );
        }
        //l4bjiy551ycsnli32bjezm45
        //Referer: http://jwgl.fafu.edu.cn/(ltxrg4j5l5ocra45okz51045)/default2.aspx
        $url = 'http://jwgl.fafu.edu.cn/('.$this->temp[3].')/content.aspx ';
        $referer = 'http://jwgl.fafu.edu.cn/('.$this->temp[3].')/xs_main.aspx?xh=3166016076';
        $result = $this->curl_request($referer, '', $url);
        $result = iconv('gb2312', 'utf-8//IGNORE', $result);
        preg_match('/<span id=\"xhxm\">(.*)<\/span>/', $result, $name);
        if (empty($name)) {
            echo '得不到您的姓名';
        }
        var_dump($name);
        $this->getkebiao();
    }

    /*
     *此处的作用是：
     *得到校网上老师和对应课程的数组
     *
     */
    function getkebiao()
    {

        $post_m['xh'] = $this->sid;
        $post_m['xm'] = iconv('utf-8', 'gb2312', '');
        $post_m['gnmkdm'] = 'N121603';
        $temp_info = http_build_query($post_m);
        $referer = 'http://jwgl.fafu.edu.cn/('.$this->temp[3].')/xs_main.aspx?xh=' . $this->sid;
        $url = 'http://jwgl.fafu.edu.cn/('.$this->temp[3].')/xskbcx.aspx?' . $temp_info;
        $result = $this->curl_request($url, '', $referer);
        $result = iconv('gb2312', 'utf-8', $result);
        if (empty($result)) {
            return array(
                'status' => "0",
                'message' => "得到课表信息失败",
            );
        }
        print_r($result);
        $pattern = '/<td align=\"Center\" rowspan=\"2\"( width=\"7%\"){0,1}>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)<\/td>/';
        preg_match_all($pattern, $result, $mm);
        $info_tea_cou_a = array_combine($mm[2], $mm[5]);
        $pattern = '/<td align=\"Center\" rowspan=\"2\"( width=\"7%\"){0,1}>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>(<br><font color=\'red\'>.*?<\/font><br>){0,1}<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)<br>([^<]*?)(<br><br><font color=\'red\'>.*?<\/font>){0,1}<\/td>/';
        preg_match_all($pattern, $result, $tt);
        $info_tea_cou_b = array_combine($tt[2], $tt[5]);
        $info_tea_cou_c = array_combine($tt[8], $tt[11]);
        $info_temp = array_merge($info_tea_cou_a, $info_tea_cou_b, $info_tea_cou_c);
        if (empty($info_temp)) {
            return array(
                'status' => "0",
                'message' => "您的课表信息为空",
            );
        }

        return array(
            'status' => "1",
            'message' => $info_temp,
        );

    }

    /*
     *此处的作用是：
     *得到学生校网上的部分信息，包括出生日期，专业，学院，联系方式，入学日期，照片
     *
     */
    function getPerInfo($temp)
    {

        $post_m['xh'] = $this->sid;
        $post_m['xm'] = iconv('utf-8', 'gb2312', '');
        $post_m['gnmkdm'] = 'N121501';
        $temp_info = http_build_query($post_m);
        $referer = 'http://jwgl.fafu.edu.cn/(l4bjiy551ycsnli32bjezm45)/xs_main.aspx?xh=' . $this->sid;
        $url = 'http://jwgl.fafu.edu.cn/(l4bjiy551ycsnli32bjezm45)/xsgrxx.aspx?' . $temp_info;
        $result = $this->curl_request($url, '', $referer);
        if (empty($result)) {
            var_dump(array(
                'status' => "0",
                'message' => "得到个人信息失败",
            ));
        }
        $result = iconv('gb2312', 'utf-8', $result);
        preg_match('/\"lbl_csrq\">(.*)<\/span>/', $result, $temp_info);
        $info['birthday'] = $temp_info[1];
        preg_match('/\"lbl_zymc\">(.*)<\/span>/', $result, $temp_info);
        $info['profession'] = $temp_info[1];
        preg_match('/\"lbl_xy\">(.*)<\/span>/', $result, $temp_info);
        $info['depart'] = $temp_info[1];
        preg_match('/\"lbl_lxdh\">(.*)<\/span>/', $result, $temp_info);
        $info['phone'] = $temp_info[1];
        preg_match('/\"lbl_rxrq\">(.*)<\/span>/', $result, $temp_info);
        $info['entry_date'] = $temp_info[1];
        preg_match('/\"lbl_xzb\">(.*)<\/span/', $result, $temp_info);
        $info['class'] = $temp_info[1];
        preg_match('/id=\"xszp\" src=\"(.*?)\" /', $result, $temp_info);
        $info['photo'] = $temp_info[1];
        $photo_add = 'http://211.87.155.19/(' . $temp[1] . ')/readimagexs.aspx?xh=' . $this->sid;
        GrabImage($photo_add, $this->sid);
        if (empty($info)) {
            var_dump(array(
                'status' => "0",
                'message' => "您的个人信息为空",
            ));
        }
        var_dump(array(
            'status' => "1",
            'message' => $info,
        ));
    }

   //获取验证码
    function GetSecertCode()
    {
        $temp = $this->getView();
        $temp = $temp['message'];
        $url = 'http://jwgl.fafu.edu.cn/(' . $temp[1] . ')/CheckCode.aspx ';
        echo '<img src=' . $url . '/ >';
        echo '<form action=\'http://z.cn/sample/moni/login\' method=\'post\'>'.
            '文本框:<input type=\'text\' name=\'text\'>'.
            '<input type=\'submit\' value=\'提交\',name=\'sub\'>'.
            '</form>';
        $this->temp=$temp;
    }

    public function test()
    {

//        //$temp = $this->getView();
//        $cc = $this->login($temp);
////        //$aa = $this->getPerInfo($temp['message']);
////        $bb = $this->getkebiao($temp['message']);
//         print_r($cc);
//        print_r($bb);
    }

    public function hahahah()
    {
        echo 'tetttt';
    }
}