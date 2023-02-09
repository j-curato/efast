<?php

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
        <?= Html::a('Create Fund Source Type', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
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
                    return Html::a("<i class='fa fa-eye'></i>", ['view', 'id' => $model->id])
                        . ' ' .
                        Html::a("<i class='fa fa-pencil'></i>", ['update', 'id' => $model->id], ['class' => 'modalButtonCreate']);
                }
            ],
        ],
    ]); ?>


</div>
<?php $this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]) ?>