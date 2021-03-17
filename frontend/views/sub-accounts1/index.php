<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubAccounts1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Accounts1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts1-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        // Html::a('Create Sub Accounts1', ['create'], ['class' => 'btn btn-success']) 

        ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Upload</button>
    </p>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="jev/604b22cad30a6_sample_sub_account1_import.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="ledger"> SELECT GENERAL LEDGER</label>
                    <?php
                    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
                    ?>
                    <?php
                    $form = ActiveForm::begin([
                        'action' => ['sub-accounts1/import'],
                        'method' => 'POST',
                        'id' => 'formupload',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    echo Select2::widget([
                        'data' => ArrayHelper::map($ledger, 'id', 'name'),
                        'id' => 'chart_id',
                        'name' => 'chart_id',
                        'options' => ['placeholder' => 'Select a ledger'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            'id',
            // 'chart_of_account_id',
            'object_code',
            [

                'label' => 'Object Code',
                'attribute' => 'name',
                'options' => [
                    'style' => 'word-wrap: break-word; width: 100px'
                ]
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
                        <input type="text" class="form-control" id="sub_account1_id" style="display: none;">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="save"> Submit</button>
                </div>
            </div>

        </div>
    </div>

</div>

<?php
SweetAlertAsset::register($this);
$script = <<<JS


        $(document).ready(function(){
            var at =''
            var id=''
          
            $('.add-sub').click(function(){
              id =  document.getElementById('sub_account1_id').value=$(this).val()
            })
            $('#save').click(function(){
             at = document.getElementById('account_title').value
            //  id = document.getElementById('chart_id').value
            console.log (at)
            $.ajax({
                type:'POST',
                url:window.location.pathname + '?r=sub-accounts1/create-sub-account' ,
                data:{
                    account_title:at,
                    id:id,
                },
                success:function(data){
                    var res = JSON.parse(data)
                    console.log(res)
                    if (res=='success'){
                        console.log("qweqweqw")
                        swal( {
                        icon: 'success',
                        title: "Successfuly Added",
                        type: "success",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                    $('#myModal').modal('hide');
                    }
                    else{
                        swal( {
                        icon: 'success',
                        title:  "error",
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