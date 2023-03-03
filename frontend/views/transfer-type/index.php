<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransferTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transfer Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-type-index">


    <p>
        <?= Html::a('Create Transfer Type', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Transfer Type'
        ],
        'columns' => [

            'type',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class])
?>