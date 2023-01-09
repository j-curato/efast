<?php

use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplementalPpmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplemental Ppmps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplemental-ppmp-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD SCANNED COPY</h4>
                </div>
                <div class='modal-body'>

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
        <?= Html::a('Create Supplemental Ppmp', ['create'], ['class' => 'btn btn-success']) ?>
        <!-- <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Upload Soft Copy</button> -->
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 

    if (Yii::$app->user->can('super-user')) {
        $columns = [

            'budget_year',
            'cse_type',
            'serial_number',
            'office_name',
            'division',
            'division_program_unit_name',
            'activity_name',


            [
                'attribute' => 'total_amount',
                'value' => function ($model) {
                    return number_format($model->total_amount, 2);
                }
            ],
            [
                'attribute' => 'balance',
                'value' => function ($model) {
                    return number_format($model->balance, 2);
                }
            ],
            'ttl_qty',
            'prepared_by',
            'reviewed_by',
            'approved_by',
            'certified_avail',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id]);
                }
            ]
        ];
    } else {
        $columns = [
            'budget_year',
            'serial_number',
            'division_program_unit_name',
            'activity_name',
            'total_amount',
            'ttl_qty',
            'prepared_by',
            'reviewed_by',
            'approved_by',
            'certified_avail',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id]);
                }
            ]
        ];
    }
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
<?php
$script = <<<JS
            var i=false;
            $('#import').on('beforeSubmit',function(e){
                // $(this).unbind();
                e.preventDefault();
                    
                //  $("#employee").on("pjax:success", function(data) {
                    //   console.log(data)
                    // });
                    
                    if (!i){
                        i=true;
                        $.ajax({
                            url: window.location.pathname + "?r=supplemental-ppmp/import",
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
                                        title: "Successfuly Added",
                                        type: "success",
                                        timer:3000,
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },function(){
                                        location.reload();
                                    })
                                }
                                else{
                                    const error_message = res.error_message.file[0]
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

             
        
JS;
$this->registerJs($script);
?>