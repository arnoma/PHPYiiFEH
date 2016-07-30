<?php
/**
 * Created by PhpStorm.
 * User: arnoma2015
 * Date: 16/7/27
 * Time: 下午5:50
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Country;


//控制器:  负责处理请求和响应  控制器从应用主题接管控制后会分析请求数据并传送模型,传送模型结果到视图,最后生成响应信息



class CountryController extends Controller {

    public function actionIndex()
    {
        $query = Country::find();

        $pagination = new Pagination([
            'defaultPageSize'=>5,
            'totalCount'=>$query->count()
        ]);

        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        //其实是单例,第一次会创建
        Yii::$app->db;
        Yii::$app->user;
        Yii::$app->cache;


        return $this->render('index',[
           'countries'=>$countries,
            'pagination'=>$pagination
        ]);

    }

}
