<?php

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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=pr-stock/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
        <button class="btn btn-warning" type='button' id="update_cloud">Update Cloud</button>
        <!-- <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button> -->
    </p>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD </h4>
                </div>
                <div class='modal-body'>
                    <center><a href="/afms/frontend/web/import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['pr-stock/import'],
                        'method' => 'post',
                        'id' => 'formupload',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    // echo '<input type="file">';
                    echo FileInput::widget([
                        'name' => 'file',
                        // 'options' => ['multiple' => true],
                        'id' => 'fileupload',
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => true,
                        ]
                    ]);

                    ActiveForm::end();


                    ?>

                </div>
            </div>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Stocks'
        ],
        'columns' => [
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
                'class' => 'kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none']
            ],
        ],
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