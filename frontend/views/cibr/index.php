<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CibrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cibrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cibr-index">


    <p>
        <?php
        $action_display = 'display:none';
        if (Yii::$app->user->can('create_cibr')) {
            $action_display = '';
        }
        echo   Html::a('Create Cibr', ['create'], ['class' => 'btn btn-success']);
        // }

        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of CIBR',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            [
                'label' => 'Province',
                'attribute' => 'province',
                'value'=>'bankAccount.province'
            ],
            [
                'label' => 'Bank Account',
                'value' => function ($model) {
                    $account = '';
                    if (!empty($model->bank_account_id)) {
                        $account = $model->bankAccount->account_number . '-' . $model->bankAccount->account_name;
                    }
                    return $account;
                }
            ],
            [
                'label' => 'Draft/Final',
                'attribute' => 'is_final',
                'value' => function ($model) {
                    $final = $model->is_final === 1 ? $final = 'Final' : 'Draft';
                    return $final;
                }
            ],


            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => $action_display],
                'updateOptions' => ['style' => $action_display]
            ],
        ],
    ]); ?>


</div>