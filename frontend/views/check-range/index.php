<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CheckRangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Check Ranges';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="check-range-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=check-range/create'),
            'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
        ]); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    $gridColumn = [

        'id',
        'from',
        'to',
        'province',

        ['class' => 'yii\grid\ActionColumn'],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Check Range'
        ],
        'toolbar' => [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns'  => $gridColumn,
                    'filename' => 'Liquidations',
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_HTML => false,

                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'columns' => $gridColumn
    ]); ?>


</div>
<?php
SweetAlertAsset::register($this);
$script = <<<JS
  
         $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $(document).ready(()=>{
            console.log(Date.now())
        })
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

JS;
$this->registerJs($script);
?>