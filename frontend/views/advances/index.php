<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advances-index">

    <?php if (Yii::$app->user->can('create_advances')) { ?>
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Create Advances', ['create'], ['class' => 'btn btn-success']) ?>
            <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
        </p>

        <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">UPLOAD Cash Disbursement</h4>
                    </div>
                    <div class='modal-body'>
                        <center><a href="import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                        <hr>
                        <?php


                        $form = ActiveForm::begin([
                            'action' => ['advances/import'],
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
    <?php } ?>
    <?php

    // ADVANCE ACCOUNTING ENTRIES AND MODEL NAA SA CONTROLLER GE CHANGE
    $gridColumn = [

        // 'advances.nft_number',
        'nft_number',
        'r_center_name',
        'province',
        'fund_source',
        'fund_source_type',
        [
            'label' => 'Amount',
            'attribute' => 'amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]
        ],
        [
            'label' => 'Total Liquidation',
            'attribute' => 'total_liquidation',
            'hAlign' => 'right',
            'format' => ['decimal', 2]
        ],
        [
            'label' => 'Balance',
            'hAlign' => 'right',
            'value' => function ($model) {
                return $model->amount - $model->total_liquidation;
            },
            'format' => ['decimal', 2]
        ],
        'dv_number',
        'payee',
        'particular',
        'reporting_period',
        'mode_of_payment',
        'check_number',
        'check_date',
        'report_type',
        'object_code',

        [
            'label' => 'action',
            'format' => 'raw',
            'value' => function ($model) {

                $t = yii::$app->request->baseUrl . "/index.php?r=advances/update&id=$model->advances_id";
                $r = yii::$app->request->baseUrl . "/index.php?r=advances/view&id=$model->advances_id";
                $color = $model->is_deleted === 0 ? 'btn-danger' : 'btn-success';
                return ' ' . Html::a('', $r, ['class' => 'btn-xs fa fa-eye']) . ' '
                    . Html::a('', $t, ['class' => 'btn-xs fa fa-pencil'])
                    . Html::button('', [
                        'class' => 'btn-xs fa fa-ban disable_button ' . $color,
                        'type' => 'button',
                        'data-val' => $model->entry_id,
                        'data-disable'=>$model->is_deleted
                    ]);
            }
        ],

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Advances',
        ],
        'toolbar' => [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns'  => $gridColumn,
                    'filename' => 'Advances',
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
        ]
    ]); ?>


</div>
<!-- 
    <style>
        .grid-view td {
            white-space: normal;
            width: 10rem;
            padding: 0;
        }
    </style> -->

<?php
SweetAlertAsset::register($this);
$script = <<<JS

        $('.disable_button').click(function(){
      
            var id =$(this).attr('data-val')
            var disable_text = 'Disable'
            if ($(this).attr('data-disable') == 10){
                disable_text='Activate'
            }
         
            swal({
            title: "Are you sure you want to "+disable_text+" this item?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Confirm',
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
             },  function(isConfirm){
                      if (isConfirm){
                    $.ajax({
                        type:"POST",
                        url:window.location.pathname + "?r=advances/disable",
                        data:{
                            id:id
                        },
                        success:function(data){
                            var res = JSON.parse(data)
                          
                            if(res.isSuccess){
                                swal({
                                        title:'Success',
                                        type:'success',
                                        button:false,
                                        timer:3000,
                                    },function(){
                                        location.reload(true)
                                    })
                            }
                            // else{
                            //     swal({
                            //             title:"Error Cannot Cancel",
                            //             text:res.cancelled,
                            //             type:'error',
                            //             button:false,
                            //             timer:3000,
                            //         })
                            // }

                        }
                    })


                 } 
             })
        })
    
JS;
$this->registerJs($script);

?>