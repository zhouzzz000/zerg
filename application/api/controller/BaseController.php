<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/19
 * Time: 16:40
 */

namespace app\api\controller;

use app\api\service\Token;
use think\Controller;

class BaseController extends Controller
{
    public static function checkPrimaryScope()
    {
        Token::needPrimaryScope();
    }
    public static function checkExclusiveScope()
    {
        Token::needExclusiveTokenVar();
    }
}