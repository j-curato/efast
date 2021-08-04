<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TrackingSheetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tracking Sheets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tracking-sheet-index">


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=tracking-sheet/create'), 'id' => 'modalButtoncreate',
            'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
        ]); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Tracking Sheets',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',

            [
                'label' => 'Payee',
                'attribute'=>'payee_id',
                'value' =>  'payee.account_name',
            ],
            [
                'label' => 'Ors Number',
                'attribute'=>'process_ors_id',
                'value' =>  'processOrs.serial_number',
            ],
            
            'tracking_number',
            'particular:ntext',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<?php
$script = <<< JS
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
JS;
$this->registerJs($script);
?>