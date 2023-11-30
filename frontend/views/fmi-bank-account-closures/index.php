<?php

use yii\helpers\Html;
use kartik\grid\GridView;

//'created_at',
/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiBankAccountClosureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Bank Account Closures';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-bank-account-closure-index">

    <p>
        <?= Yii::$app->user->can('create_fmi_bank_closure') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Bank Account Closures',
        ],
        'columns' => [
            'serial_number',
            'fk_fmi_subproject_id',
            'reporting_period',
            'date',
            //'bank_certification_link:ntext',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_fmi_bank_closure') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>