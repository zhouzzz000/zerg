<?php
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
function getView()
{
    $url = 'http://jwgl.fafu.edu.cn/default2.aspx';
    $result = curl_request($url);
    if (empty($result)) {
        return array(
            'status' => "0",
            'message' => "模拟登陆失败，网址可能以改变",
        );
    }
    preg_match('/Location: \/\((.*)\)/', $result, $temp);
//    var_dump($temp);
    $pattern = '/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is';
    preg_match_all($pattern, $result, $matches);
//    var_dump($matches);
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
$temp = getView()['message'];
$ss = "文本框";
?>
<?php
    var_dump($temp);
    $url='http://jwgl.fafu.edu.cn/(' . $temp[1] . ')/CheckCode.aspx' ;
    echo '<img src='.$url.' \/>';
?>
<form action='http://z.cn/sample/moni/login' method='post'>
    <?php echo $ss; ?>:<input type='text' name='serectCode'>
    <input type='submit' value='提交' name='sub'>
    <input type="hidden" name="data1" value="<?php echo $temp[0] ?>">
    <input type="hidden" name="data3" value="<?php echo $temp[1] ?>">
    <input type="hidden" name="data2" value="<?php echo $temp[2] ?>">
</form>
