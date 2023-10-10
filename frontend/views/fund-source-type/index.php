<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FundSourceTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fund Source Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-source-type-index">


    <p>
        <?= Yii::$app->user->can('create_fund_source_type') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Fund Source Types'
        ],
        'columns' => [

            'name',
            'division',

            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_fund_source_type') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
<?php $this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]) ?>