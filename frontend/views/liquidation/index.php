<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\User;
use kartik\grid\GridView;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\export\ExportMenu;
use app\models\LiquidationViewSearch;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LiquidataionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liquidations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-index">


    <?php if (Yii::$app->user->can('liquidation')) { ?>
        <p>
            <?= Html::a('Create Liquidation', ['create'], ['class' => 'btn btn-success']) ?>
            <!-- <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button> -->

            <?php
            if (Yii::$app->user->can('ro_accounting_admin')) {
                echo " <button class='btn btn-success' data-target='#updateUacsModal' data-toggle='modal'>Update Uacs</button>";
            }
            ?>
        </p>

        <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">UPLOAD Cash Disbursement</h4>
                    </div>
                    <div class='modal-body'>
                        <center><a href="import_formats/update_uacs_format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                        <hr>
                        <?php


                        $form = ActiveForm::begin([
                            'action' => ['liquidation/import'],
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

        <div class="modal fade" id="updateUacsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            'id' => 'updateUacs',
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ], // important
                        ]);

                        // echo '<input type="file">';
                        echo "<br>";
                        echo FileInput::widget([
                            'name' => 'file',
                            // 'options' => ['multiple' => true],
                            'id' => 'updateUacsfile',
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


    <?php }
    ?>

    <!-- LIQUIDATION ENTRIES AND MODEL NAA SA INDEX CONTROLLER GE CHANGE -->


    <?php


    $gridColumn = [
        'id',
        'reporting_period',
        'dv_number',
        'check_date',
        'check_number',
        'fund_source',
        [
            'label' => 'Particular',
            'value' => function ($model) {
                if (!empty($model->particular)) {
                    $particular = $model->particular;
                } else {
                    $particular = $model->transaction_particular;
                }
                return $particular;
            }
        ],
        [
            'label' => 'Payee',
            'value' => function ($model) {
                if (!empty($model->payee)) {
                    $payee = $model->payee;
                } else {
                    $payee = $model->transaction_payee;
                }
                return $payee;
            }
        ],
        'object_code',
        'account_title',
        'withdrawals',
        'vat_nonvat',
        'expanded_tax',
        'liquidation_damage',
        'gross_payment',
        'province',

    ];
    $user_data = User::getUserDetails();
    $province = strtolower($user_data->employee->office->office_name);
    $viewSearchModel = new LiquidationViewSearch();
    $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);
    $viewDataProvider->pagination = ['pageSize' => 10];
    $viewColumn = [
        'province',
        // [
        //     'attribute' => 'province',
        //     'value' => function ($model) {


        //         return strtoupper($model->province);
        //     }
        // ],
        'check_date',
        'check_number',
        'dv_number',
        'reporting_period',
        'tracking_number',

        [
            'label' => 'Payee',
            'attribute' => 'payee',
            'value' => function ($model) {

                if (!empty($model->tr_payee)) {
                    $payee = $model->tr_payee;
                } else {
                    $payee = $model->payee;
                }
                return $payee;
            }
        ],
        [
            'label' => 'Particular',
            'attribute' => 'particular',
            'value' => function ($model) {

                if (!empty($model->tr_particular)) {
                    $particular = $model->tr_particular;
                } else {
                    $particular = $model->particular;
                }
                return $particular;
            }
        ],
        'account_name',

        [
            'label' => 'Total Disbursements',
            'attribute' => 'total_withdrawal',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Sales Tax (VAT/Non-VAT)',
            'attribute' => 'total_vat',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Income Tax (Expanded Tax)',
            'attribute' => 'total_expanded',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Liquidation Damage',
            'attribute' => 'total_liquidation_damage',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Gross Payment',
            'attribute' => 'gross_payment',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],

        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::a("", ['view', 'id' => $model->id], ['class' => 'btn-xs  fa fa-eye']);
                // return $query['total'];
            },
            'hiddenFromExport' => true,
            'vAlign' => 'middle',
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $viewDataProvider,
        'filterModel' => $viewSearchModel,
        'columns' => $viewColumn,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Liquidations',
        ],
        'pjax' => true,
        'toolbar' => [
            [

                'content' => "<form  id='export_filter'>
                <div class='row'>
                    <div class='col'>" .
                    DatePicker::widget([
                        'name' => 'from_reporting_period',
                        'id' => 'from_reporting_period',

                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ],
                        'options' => [
                            'placeholder' => 'From',
                            'readOnly' => true,
                            'style' => 'background-color:white'

                        ],
                    ])
                    . "</div>
                    <div class='col'>" .
                    DatePicker::widget([
                        'name' => 'to_reporting_period',
                        'id' => 'to_reporting_period',

                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ],
                        'options' => [
                            'placeholder' => 'To',
                            'readOnly' => true,
                            'style' => 'background-color:white'

                        ],
                    ])
                    . "</div>
                    <div class='col'><button class='btn btn-primary' type='submit'>Export</button></div>
                </div>
              
                </form>",
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ]
    ]);

    ?>

</div>

<style>
    .grid-view td {
        white-space: normal;
        font-size: 12px;
    }

    .from_reporting_period {
        width: 100%;
    }
</style>

<?php
SweetAlertAsset::register($this);
$script = <<<JS

        var i = false
            $('#export_filter').submit(function(e){
                e.preventDefault();
                $.ajax({
                    container: "#employee", 
                    type:'POST',
                    url: window.location.pathname + '?r=liquidation/export',
                    data:$('#export_filter').serialize(),
                    success:function(data){
                        var res = JSON.parse(data)
                        console.log(res)
                        window.open(res)
                    }
                 
                })
            })
            $('#updateUacs').submit(function(e){
                // $(this).unbind();
                e.preventDefault();
                    
                //  $("#employee").on("pjax:success", function(data) {
                    //   console.log(data)
                    // });
                    
                    if (!i){
                        i=true;
                        $.ajax({
                            url: window.location.pathname + '?r=liquidation/update-uacs',
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
                                window.location.href = window.location.pathname + "?r=liquidation"
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
      
             
        
JS;
$this->registerJs($script);
?>

<!-- "<form id='export_filter'>" .
                    "<span >" .
                    DatePicker::widget([
                        'name' => 'from_reporting_period',
                        'id' => 'from_reporting_period',
                        'options' => [
                            'style' => 'width:100px'
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ],
                        'options' => [
                            'placeholder' => 'From',
                            'readOnly' => true,
                            'style' => 'background-color:white;width:120px',
                            'class' => 'col-sm-3'
                        ],
                    ]) .
                    '</span>'
                    .
                    DatePicker::widget([
                        'name' => 'to_reporting_period',
                        'id' => 'to_reporting_period',
                        'options' => [
                            'style' => 'width:100px'
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ],
                        'options' => [
                            'placeholder' => 'To',
                            'readOnly' => true,
                            'style' => 'background-color:white;width:120px'

                        ],
                    ]) .

                    "<button class='btn btn-primary' type='submit'>Export</button>", -->