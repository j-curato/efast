<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrOfficeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pr Offices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-office-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=pr-office/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
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
                        'action' => ['pr-office/import'],
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
            'heading' => 'List of Transactions',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'office',
            'division',
            'unit',
            'responsibility_code',
            [
                'attribute' => 'fk_unit_head',
                'value' => function ($model) {
                    $employee = '';
                    if (!empty($model->fk_unit_head)) {
                        $employee = $model->unitHead->f_name . ' ' . $model->unitHead->m_name[0] . '. ' . $model->unitHead->l_name;
                    }
                    return $employee;
                }
            ],

            [

                'class' => '\kartik\grid\ActionColumn',
            ],
        ],
    ]); ?>


</div>

<?php
SweetAlertAsset::register($this);
$script = <<<JS
            var i=false;
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

        
            $('#import').submit(function(e){
                // $(this).unbind();
                e.preventDefault();
                    
                //  $("#employee").on("pjax:success", function(data) {
                    //   console.log(data)
                    // });
                    
                    if (!i){
                        i=true;
                        $.ajax({
                            url: window.location.pathname + '?r=transaction/import-transaction',
                            type:'POST',
                            data:  new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,
                            success:function(data){
                                console.log(data)
                                var res = JSON.parse(data)
                        //         // break;
                        //         // $('#uploadmodal').close()
                        //         console.log(i)
                                
                        if (res.isSuccess){
                            swal( {
                                icon: 'success',
                                title: "Successfuly Added",
                                type: "success",
                                timer:3000,
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },function(){
                                window.location.href = window.location.pathname + "?r=transaction"
                            })
                        }
                        else{
                            swal( {
                                icon: 'error',
                                title: res.error,
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
            $(document).ready(function(){
             })
             
        
JS;
$this->registerJs($script);
?>