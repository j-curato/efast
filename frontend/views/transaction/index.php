<?php

use app\models\SubAccounts1;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use aryelds\sweetalert\SweetAlertAsset;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .modal-wide {
        width: 90%;
    }
</style>
<?php
Modal::begin(
    [
        //'header' => '<h2>Create New Region</h2>',
        'id' => 'transactionmodal',
        'size' => 'modal-wide',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]
);
echo "<div class='box box-success' id='modalContent'></div>";
Modal::end();
?>
<div class="transaction-index">


    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Transaction', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=transaction/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>


        <?php
        $whitelist = array('127.0.0.1', "::1");

        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

            if (Yii::$app->user->can('super-user')) {
                echo "<button type='button' class='btn btn-primary'  id ='update_local_transaction'>Update Local Transaction</button>";
            }
        }
        ?>
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
                    <label for="ledger"> SELECT GENERAL LEDGER</label>
                    <?php
                    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
                    ?>
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
        'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // [
            //     'class' => 'kartik\grid\ExpandRowColumn',
            //     'width' => '50px',
            //     'value' => function ($model, $key, $index, $column) {
            //         return GridView::ROW_COLLAPSED;
            //     },
            //     // uncomment below and comment detail if you need to render via ajax
            //     // 'detailUrl' => Url::to([ '/index.php?r=transaction/sample&id='.$model->id]),
            //     'detail' => function ($model, $key, $index, $column) {
            //         $q=SubAccounts1::findOne(2602);
            //         return Yii::$app->controller->renderPartial('view_sample', ['model' => $q]);
            //     },
            //     'headerOptions' => ['class' => 'kartik-sheet-style'],
            //     'expandOneOnly' => true
            // ],

            'id',

            [
                'label' => 'Responsibility Center',
                'attribute' => 'responsibility_center_id',
                'value' => 'responsibilityCenter.name',

            ],
            'tracking_number',

            // 'payee_id',
            // [

            // ],
            [
                'label' => 'Payee',
                'attribute' => 'payee_id',
                'value' => 'payee.account_name'
            ],
            'particular',
            // 'gross_amount',
            [
                'attribute' => 'gross_amount',
                'format' => ['decimal', 2],
            ],
            'earmark_no',
            'payroll_number',
            'transaction_date',
            //'transaction_time',

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
            ],
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>
<script>
    $(document).ready(function() {


    })
</script>
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
                $('#update_local_transaction').click(function(e) {
                    e.preventDefault()
                    $.ajax({
                        type: 'POST',
                        url: window.location.pathname + '?r=sync-database/update-local-transaction',
                        data: {id:1},
                        success: function(data) {
                            console.log(data)
                        }
                    })
                })
             })
             
        
JS;
$this->registerJs($script);
?>