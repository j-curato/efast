<?php

use app\models\SubAccounts1;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap4\Modal;
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


    <p>
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


        
            $('#import').on('beforeSubmit',function(e){
                // $(this).unbind();
                e.preventDefault();
                    
                //  $("#employee").on("pjax:success", function(data) {
                    //   console.log(data)
                    // });
                    
                    if (!i){
                        i=true;
                        $.ajax({
                            url: window.location.href,
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

             
        
JS;
$this->registerJs($script);
?>