<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashFlowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Flows';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-flow-index">


    <p>
        <?= Yii::$app->user->can('create_cash_flow') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Cash Flows'
        ],
        'columns' => [
            'specific_cashflow',
            'major_cashflow',
            'sub_cashflow1',
            'sub_cashflow2',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_cash_flow') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [\yii\web\JqueryAsset::class]
    ]
)
?>