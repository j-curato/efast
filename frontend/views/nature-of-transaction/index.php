<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NatureOfTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nature Of Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nature-of-transaction-index">


    <p>
        <?= Html::a('Create Nature Of Transaction', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' =>
        [
            'type' => 'primary',
            'heading' => 'Nature of Transactions'
        ],
        'columns' => [

            'name',
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