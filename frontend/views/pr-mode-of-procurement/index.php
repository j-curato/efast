<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrModeOfProcurementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Mode Of Procurements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-mode-of-procurement-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'], ['class' => 'btn btn-success modalButtonCreate']); ?>

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
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>
<?php
$script = <<<JS

        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

        
JS;
$this->registerJs($script);
?>