<?php
/**
 * Created by PhpStorm.
 * User: zhouzzz
 * Date: 2018/7/14
 * Time: 19:27
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;
use think\Controller;

class Category extends Controller
{
        public function getAllCategories()
        {
            $categories = CategoryModel::all([],'img');
            if($categories->isEmpty())
            {
                throw new CategoryException();
            }
            return $categories;
        }
}