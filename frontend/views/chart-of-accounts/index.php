<?php

use app\models\MajorAccounts;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChartOfAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chart Of Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=chart-of-accounts/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
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
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        'options' => ['id' => 'table-grid'],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Recommendation List',
            //'after'=>$after,0
        ],

        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [


            'id',
            // [
            //     'class' => 'kartik\grid\ExpandRowColumn',
            //     'width' => '50px',
            //     'value' => function ($model, $key, $index, $column) {
            //         return GridView::ROW_COLLAPSED;
            //     },
            //     // uncomment below and comment detail if you need to render via ajax
            //     // 'detailUrl' => Url::to([ '/index.php?r=transaction/sample&id='.$model->id]),
            //     'detail' => function ($model, $key, $index, $column) {
            //         return Yii::$app->controller->renderPartial('view', ['model' => $model]);
            //     },
            //     // 'headerOptions' => ['class' => 'kartik-sheet-style'],
            //     'expandOneOnly' => true
            // ],

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
            // <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
            ['class' => 'yii\grid\ActionColumn'],



        ],
    ]); ?>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->

            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="account_title">Account Title:</label>
                        <input type="account_title" class="form-control" id="account_title">
                        <input type="text" class="form-control " id="chart_id">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="save"> Submit</button>
                </div>
            </div>

        </div>
    </div>

</div>
<style>
    #chart_id {
        display: none;
    }
</style>

<?php
SweetAlertAsset::register($this);
$script = <<<JS
            $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

        $(document).ready(function(){
            var at =''
            var id=''
          
            $('.add-sub').click(function(){
              id =  document.getElementById('chart_id').value=$(this).val()
            })
            $('#save').click(function(){
             at = document.getElementById('account_title').value
            //  id = document.getElementById('chart_id').value
            console.log (at)
            $.ajax({
                type:'POST',
                url:window.location.pathname + '?r=chart-of-accounts/create-sub-account' ,
                data:{
                    account_title:at,
                    id:id,
                },
                success:function(data){
                    // var res = JSON.parse(data)
                    console.log(data)

    
                    if (data=='success'){
                    $('#myModal').modal('hide');
   
                        swal( {
                        icon: 'success',
                        title: "Successfuly Added",
                        type: "success",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                    }
                    else{
                        swal( {
                        icon: 'error',
                        title:  res.name,
                        type: "error",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                    }
                },
                beforeSend: function(){
                   setTimeout(() => {
                   console.log('loading');
                       
                   }, 5000);
                },
                complete: function(){
                    $('#loading').hide();
                }
                

            })
        })
        })


JS;
$this->registerJs($script);
?>

<?php

$js = <<<JS



JS;
$this->registerJs($js);
?>