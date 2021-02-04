<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Payee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
      ],
       'floatHeaderOptions'=>[
           'top'=>50,
           'position'=>'absolute',
         ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'account_name',
            'registered_name',
            'contact_person',
            'registered_address',
            //'contact',
            //'remark',
            //'tin_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
