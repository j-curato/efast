<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\hre\HreAreasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hre Areas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hre-areas-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Add Areas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
             'formatter'=> ['class'=>'yii\i18n\Formatter','nullDisplay'=>'-'],
         'panel' => [
              'type' => GridView::TYPE_PRIMARY,
              'heading' => 'List of Areas',
              //'after'=>$after,0
              // 'before'=> $add ,
        ],

         'floatHeaderOptions'=>[
             'top'=>50,
             'position'=>'absolute',
           ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',
            // [
            //     'label'=>'City/Municipality',
            //     'value'=>function($model){
            //         $city_name = $model->getCityName($model->city_code);
            //         if($city_name){
            //             return $city_name;
            //         }else{
            //             return '-';
            //         }
            //     }
            // ],
            // 'code',
            // 'is_active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
