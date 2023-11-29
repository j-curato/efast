<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiBankDepositsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FMI Bank Deposits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-bank-deposits-index">

    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('Create Fmi Bank Deposits', ['create'], ['class' => 'btn btn-success lrgModal']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Bank Deposits',
        ],
        'columns' => [
            'serial_number',
            'deposit_date',
            'reporting_period',
            'fk_fmi_bank_deposit_type_id',
            //'fk_fmi_subproject_id',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'lrgModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>