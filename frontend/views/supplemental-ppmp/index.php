<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplementalPpmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplemental Ppmps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplemental-ppmp-index">

    <div class="modal fade" id="uploadCsemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <div class='modal-body'>
                    <h4 class="modal-title" id="myModalLabel">IMPORT CSE/NON-CSE</h4>
                    <center><a href="import_formats/cse_import_format.xlsx">Download Template for CSE Here to avoid error during Upload.</a></center>
                    <center><a href="import_formats/non_cse_import_format.xlsx">Download Template forNON-CSE Here to avoid error during Upload.</a></center>
                    <?php
                    $form = ActiveForm::begin([
                        // 'action' => ['transaction/import-transaction'],
                        // 'method' => 'POST',
                        'id' => 'import',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    echo '<label> Select PPMP Type</label>';
                    echo Select2::widget([
                        'id' => 'supplemental_type',
                        'name' => 'supplemental_type',
                        'data' => [
                            'import-cse' => 'CSE',
                            'import-non-cse' => 'NON-CSE',
                        ],
                        'pluginOptions' => [
                            'placeholder' => 'Select Type'
                        ]
                    ]);
                    // echo '<input type="file">';
                    echo "<br>";
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

    <p>
        <?php

        if (strtotime(date('Y-m-d')) <= strtotime(date('2023-11-28'))) {
            echo Html::a('Create Supplemental Ppmp', ['create'], ['class' => 'btn btn-success']);
        }

        ?>
        <?= Yii::$app->user->can('import_supplemental_ppmp') ? Html::a('Import', ['create'], [
            'class' => 'btn btn-warning',
            'data-target' => "#uploadCsemodal", 'data-toggle' => "modal"
        ]) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    $columns = [

        'budget_year',
        'cse_type',
        'serial_number',
        'office_name',
        'division',
        'division_program_unit_name',
        'stock_activity',


        [
            'attribute' => 'gross_amt',
            'value' => function ($model) {
                return number_format($model->gross_amt ?? 0, 2);
            }
        ],
        [
            'attribute' => 'bal_amt',
            'value' => function ($model) {
                return number_format($model->bal_amt ?? 0, 2);
            }
        ],
        'ttl_qty',
        'bal_qty',
        'prepared_by',
        'reviewed_by',
        'approved_by',
        'certified_avail',
        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                    . ' ' . Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id]);
            }
        ]
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'PPMP'
        ],
        'pjax' => true,
        'columns' => $columns
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        max-width: 100rem;
        padding: 10px;
    }
</style>
<?php
SweetAlertAsset::register($this);
$script = <<<JS
            var i=false;
            $('#import').on('submit',function(e){
                e.preventDefault();
               
                    if (!i){
                        i=true;
                        $.ajax({
                            url: window.location.pathname + "?r=supplemental-ppmp/"+$('#supplemental_type').val(),
                            type:'POST',
                            data:  new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,
                            success:function(data){
                                var res = JSON.parse(data)
                                if (res.isSuccess){
                                    swal( {
                                        icon: 'success',
                                        title: "Successfully Imported",
                                        type: "success",
                                        timer:3000,
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },function(){
                                        location.reload();
                                    })
                                }
                                else{
                                    const error_message = res.error_message
                                    swal( {
                                        icon: 'error',
                                        title: error_message,
                                        type: "error",
                                        timer:10000,
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    })
                                    i=false;
                                }
                    },
                    
                    
                    
                    // data:$('#import').serialize()
                })
                
                 return false; 
                }
                
            })
            var i=false;
JS;
$this->registerJs($script);
?>