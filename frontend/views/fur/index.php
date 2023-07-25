<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FURs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fur-index">


    <p>
        <?= Html::a('Create Fur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            // 'heading' => 'List of Areas',
        ],
        'export' => false,
        'columns' => [

            'reporting_period',
            [
                'label' => 'Province',
                'attribute' => 'province',
                'value' => 'bankAccount.province'
            ],
            [
                'label' => 'Bank Account',
                'attribute' => 'bank_account_id',
                'value' => function ($model) {
                    $account = '';
                    if (!empty($model->bank_account_id)) {
                        $account = $model->bankAccount->account_number . '-' . $model->bankAccount->account_name;
                    }
                    return $account;
                }
            ],



            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none'],
                // 'updateOptions' => ['style' => 'display:none']
            ]
        ],
    ]); ?>


</div>