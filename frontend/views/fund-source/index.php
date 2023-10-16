<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FundSourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fund Sources';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-source-index">


    <p>
        <?= Yii::$app->user->can('create_fund_source') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'name',
            'description',
            'note',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_fund_source') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>