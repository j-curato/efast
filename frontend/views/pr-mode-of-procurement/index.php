<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use app\components\helpers\MyHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrModeOfProcurementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Mode Of Procurements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-mode-of-procurement-index">


    <p>
        <?= Yii::$app->user->can('create_mode_of_procurement') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Mode of Procurements'
        ],
        'columns' => [

            'mode_name',
            'description',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_mode_of_procurement') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [JqueryAsset::class]
    ]
);

?>