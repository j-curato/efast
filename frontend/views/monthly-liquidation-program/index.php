<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MonthlyLiquidationProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Monthly Liquidation Programs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monthly-liquidation-program-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Monthly Liquidation Program', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="/afms/frontend/web/import_formats/Transaction_Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="ledger"> SELECT YEAR</label>


                    <?php

                    $form = ActiveForm::begin([
                        // 'action' => ['transaction/import-transaction'],
                        // 'method' => 'POST',
                        'id' => 'import',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);

                    // echo '<input type="file">';
                    echo DatePicker::widget([
                        'name' => 'year',
                        'pluginOptions' => [
                            'minViewMode' => 'years',
                            'autoclose' => true,
                            'format' => 'yyyy'
                        ]
                    ]);
                    echo "<br>";
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            'amount',
            'book_id',
            'province',
            //'fund_source_type',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<script>
    let submitted = false;
    $(document).ready(function() {


        $('#import').submit(function(e) {
            e.preventDefault();
            if (submitted == false) {
                submitted = true;
                $.ajax({
                    url: window.location.pathname + '?r=monthly-liquidation-program/import',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data)
                        submitted = true;
                        var res = JSON.parse(data)

                        if (res.isSuccess) {
                            swal({
                                    icon: 'success',
                                    title: "Successfuly Added",
                                    type: "success",
                                    timer: 3000,
                                    closeOnConfirm: false,
                                    closeOnCancel: false
                                }
                                // , function() {
                                //     window.location.href = window.location.pathname + "?r=transaction"
                                // }
                            )
                        } else {
                            swal({
                                icon: 'error',
                                title: res.message,
                                type: "error",
                                timer: 10000,
                                closeOnConfirm: false,
                                closeOnCancel: false
                            })
                            submitted = false;
                        }

                    }

                    // data:$('#import').serialize()
                })
            }
        })


    })
</script>
<?php
SweetAlertAsset::register($this);

$script = <<<JS
        // $('#modalButtoncreate').click(function(){
        //     $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        // });
        // $('a[title=Update]').click(function(e){
        //     e.preventDefault();
            
        //     $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        // });
    
        
           
                
             
        
JS;
$this->registerJs($script);
?>