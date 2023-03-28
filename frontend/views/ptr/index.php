<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PtrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PTRs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ptr-index">


    <p>
        <?= Html::a('Create Ptr', ['create'], ['class' => 'btn btn-success lrgModal']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'PTRs'
        ],
        'columns' => [

            'office_name',
            'ptr_number',
            'date',
            'property_number',
            'par_number',
            'description',
            'receive_by',
            'article',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'lrgModal');
                }
            ]

        ],
    ]); ?>


</div>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]) ?>