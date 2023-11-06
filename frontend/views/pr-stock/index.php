<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-stock-index">
    <?= $this->render('/modules/upload_form', [
        'url' => 'pr-stock/import',
        'templateUrl' => 'import_formats/stock_import_format.xlsx',
        'label'=>'Import CSE Stocks'
    ]) ?>

    <p>
        <?= Yii::$app->user->can('create_stock') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
        <?= Yii::$app->user->can('import_stock') ?
            Html::a('<i class="fa fa-plus"></i> Import ', ['create'], [
                'class' => 'btn btn-success',
                'data-target' => "#uploadModal",
                'data-toggle' => "modal"
            ]) : '' ?>
        <!-- <button class="btn btn-warning" type='button' id="update_cloud">Update Cloud</button> -->
    </p>


    <?php
    $cols = [
        'bac_code',
        'stock_title',
        [
            'label' => 'Unit of Measure',
            'attribute' => 'unit_of_measure_id',
            'value' => 'unitOfMeasure.unit_of_measure'
        ],
        'budget_year',
        [
            'attribute' => 'amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                $updateBtn = Yii::$app->user->can('update_stock') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
            }
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Stocks'
        ],
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $cols,
                    'filename' => "DV",
                    'batchSize' => 10,
                    'stream' => false,
                    'target' => '_popup',

                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]

                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'columns' => $cols
    ]); ?>


</div>
<script>
    $(document).ready(function() {
        $('#update_cloud').click(function(e) {
            e.preventDefault()
            const stockapi = new Promise((resolve, reject) => {
                // RECORD ALLOTMENT API
                $.post(window.location.pathname + '?r=sync-database/pr-stocks', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=pr-stock-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })

            })
            Promise.all([
                stockapi

            ]).then(values => {

                console.log('qwer')
            }, reason => {
                console.log("Promises failed: " + reason);
            });
        })

    })
</script>
<?php
$script = <<<JS

        $('a[title=Update]').click(function(e){
            e.preventDefault();
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

JS;
$this->registerJs($script);
?>