<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentReceiveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Receives';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-recieve-index">


    <p>
        <?= Html::a('Create Document Receive', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Document Receives',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'name',
            'description',

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
<?php $this->registerJsfile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]) ?>