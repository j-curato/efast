<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use app\components\helpers\MyHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MfoPapCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MFO/PAP Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mfo-pap-code-index">


    <p>
        <?= Yii::$app->user->can('create_mfo_pap_code') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of MFO/PAP Codes'
        ],
        'columns' => [

            'code',
            'name',
            'description',
            'division',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_mfo_pap_code') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
