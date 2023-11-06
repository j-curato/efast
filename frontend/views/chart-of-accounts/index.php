<?php

use app\models\MajorAccounts;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChartOfAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chart Of Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-accounts-index">


    <p>
        <?= Yii::$app->user->can('create_chart_of_account') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success lrgModal']) : ''; ?>
        <!-- <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button> -->
    </p>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="WFP Template.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['chart-of-accounts/import'],
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
    $gridColumn = [
        [
            'label' => 'UACS',
            'attribute' => 'uacs',
        ],
        [
            'label' => 'General Ledger',
            'attribute' => 'general_ledger',
        ],
        [
            'label' => 'Account Group',
            'attribute' => 'account_group',

        ],

        [
            'label' => 'Object Code',
            'attribute' => 'majorAccount.object_code',
            'value' => 'majorAccount.object_code',

        ],

        [
            'label' => 'Major Account',
            'attribute' => 'major_account_id',
            'value' => 'majorAccount.name',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(MajorAccounts::find()->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Major Account'],
            ],
            'format' => 'raw'

        ],
        [
            'label' => 'Object Code',
            'attribute' => 'subMajorAccount.object_code',
            'value' => 'subMajorAccount.object_code'

        ],
        [
            'label' => 'SUb Major Account',
            'attribute' => 'sub_major_account',
            'value' => 'subMajorAccount.name'

        ],
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                // $t = yii::$app->request->baseUrl . '/index.php?r=chart-of-accounts/update&id=' .
                return ' ' . Html::button('<span class="">Add</span>', [
                    'data-toggle' => "modal", 'class' => '"btn btn-info btn-xs add-sub',
                    'data-toggle' => "modal", 'data-target' => "#myModal",
                    'value' => $model->id,
                ]);
            }
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        'options' => ['id' => 'table-grid'],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Chart of Accounts List',
            //'after'=>$after,0
        ],
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Detailed_Dv',
                    'batchSize' => 1,
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        // ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]

                ]),
                'options' => ['class' => 'btn-group mr-2', 'style' => 'margin-right:20px']
            ],

        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            [
                'label' => 'UACS',
                'attribute' => 'uacs',
            ],
            [
                'label' => 'General Ledger',
                'attribute' => 'general_ledger',
            ],
            [
                'label' => 'Account Group',
                'attribute' => 'account_group',

            ],

            [
                'label' => 'Object Code',
                'attribute' => 'majorAccount.object_code',
                'value' => 'majorAccount.object_code',

            ],

            [
                'label' => 'Major Account',
                'attribute' => 'major_account_id',
                'value' => 'majorAccount.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(MajorAccounts::find()->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'placeholder' => 'Major Account'],
                ],
                'format' => 'raw'

            ],
            [
                'label' => 'Object Code',
                'attribute' => 'subMajorAccount.object_code',
                'value' => 'subMajorAccount.object_code'

            ],
            [
                'label' => 'SUb Major Account',
                'attribute' => 'sub_major_account',
                'value' => 'subMajorAccount.name'

            ],

            // <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $createSubAccount1Btn  = Yii::$app->user->can('create_sub_account_1') ? Html::a('<i class="fa fa-plus"></i>', ['sub-accounts1/create', 'chartOfAccountId' => $model->id], ['class' => 'btn-xs btn-success mdModal']) : '';
                    $updateBtn = Yii::$app->user->can('update_chart_of_account') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'lrgModal']) : '';

                    return  $createSubAccount1Btn . ' ' . Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                },
                'options' => [
                    'style' => 'width:5%;'
                ]
            ]


        ],
    ]); ?>


</div>
<style>
    #chart_id {
        display: none;
    }
</style>

<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);
// $script = <<<JS
//             $('#mdModal').click(function(){
//             $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
//         });
//         $('a[title=Update]').click(function(e){
//             e.preventDefault();

//             $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
//         });

//         $(document).ready(function(){
//             var at =''
//             var id=''

//             $('.add-sub').click(function(){
//               id =  document.getElementById('chart_id').value=$(this).val()
//             })
//             $('#create_sub_account').submit(function(e){
//                 e.preventDefault()
//              at = document.getElementById('account_title').value
//             //  id = document.getElementById('chart_id').value
//             console.log (at)
//             $.ajax({
//                 type:'POST',
//                 url:window.location.pathname + '?r=chart-of-accounts/create-sub-account' ,
//                 data:$('#create_sub_account').serialize(),
//                 success:function(data){
//                     // var res = JSON.parse(data)
//                     console.log(data)


//                     if (data=='success'){
//                     $('#myModal').modal('hide');

//                         swal( {
//                         icon: 'success',
//                         title: "Successfuly Added",
//                         type: "success",
//                         timer:3000,
//                         closeOnConfirm: false,
//                         closeOnCancel: false
//                     })
//                     }
//                     else{
//                         swal( {
//                         icon: 'error',
//                         title:  res.name,
//                         type: "error",
//                         timer:3000,
//                         closeOnConfirm: false,
//                         closeOnCancel: false
//                     })
//                     }
//                 },
//                 beforeSend: function(){
//                    setTimeout(() => {
//                    console.log('loading');

//                    }, 5000);
//                 },
//                 complete: function(){
//                     $('#loading').hide();
//                 }


//             })
//         })
//         })


// JS;
// $this->registerJs($script);
?>

<?php

?>