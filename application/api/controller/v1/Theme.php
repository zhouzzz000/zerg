<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/6/26
 * Time: 19:43
 */

namespace app\api\controller\v1;
use app\api\model\Theme as ThemeModel;

use app\api\validate\IDCollection;
use app\api\validate\IDMustBePostiveInt;
use think\Controller;
use app\lib\exception\ThemeException;
class Theme extends Controller
{
    /**
     * @param $themeId
     * @return 一组theme模型
     * @url /theme?ids=id1,id2,id3...
     */
    public function getSimpleList($ids='')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);
        $result = ThemeModel::with(['topicImg','headImg'])->select($ids);
        if($result->isEmpty())
        {
            throw new ThemeException();
        }
        return $result;
    }

    /**
     * @param $id
     * @throws \app\lib\exception\ParameterException
     * @url: /theme/:id
     */
    public function getComplexOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if(!$theme)
        {
            throw new ThemeException();
        }
        return $theme;
    }
}