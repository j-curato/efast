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
        <?= Html::a('Create Cash Flow', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Cash Flos'
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
                    return MyHelper::gridDefaultAction($model->id);
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