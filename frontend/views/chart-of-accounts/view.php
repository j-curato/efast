<?php

use app\models\MajorAccounts;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\ChartOfAccounts */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Chart Of Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chart-of-accounts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['id' => 'stafflist'],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            // 'heading' => 'Memo list',
            //'after'=>$after,0
            // 'before'=> $add ,
        ],
        // 'toolbar'=> false,
        // 'pjax'=>true,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'uacs',
            'general_ledger',
            'account_group',

            [
                'label' => 'Object Code',
                'attribute' => 'major_id',
                'value' => function ($model) {
                    return $model->majorAccount->object_code;
                },
                'filter'=>ArrayHelper::map(MajorAccounts::find()->select('name,id')->asArray()->all(), 'id','name')
            ],
            [
                'label' => 'Major Account',
                'attribute' => 'major_id',
                'value' => function ($model) {
                    return $model->majorAccount->name;
                }
            ],
            [
                'label' => 'Object Code',
                'attribute' => 'sub_major_id',
                'value' => function ($model) {
                    return $model->subMajorAccount->object_code;
                }
            ],
            [
                'label' => 'Sub Major Account',
                'attribute' => 'sub_major_id',
                'value' => function ($model) {
                    return $model->subMajorAccount->name;
                }
            ],
            'enable_disable',
            'current_noncurrent',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>


</div>