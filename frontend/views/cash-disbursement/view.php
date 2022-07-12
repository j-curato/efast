<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cash-disbursement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cash Disbursement', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>


        <?php
        if ($model->is_cancelled) {
            echo "<button class='btn btn-success' id='cancel' style='margin:5px'>Activate</button>";
        } else {
            echo "<button class='btn btn-danger' id='cancel' style='margin:5px'>Cancel</button>";
        }
        echo "<input type='text' id='cancel_id' value='$model->id' style='display:none;'/>";
        $t = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/view&id=$model->dv_aucs_id";
        echo  Html::a('DV Link', $t, ['class' => 'btn btn-info ', 'style' => 'margin:3px']);
        if (!empty($model->jevPreparation)) {
            $jev_link = yii::$app->request->baseUrl . "/index.php?r=jev-preparation/view&id={$model->jevPreparation->id}";
            echo  Html::a('JEV ', $jev_link, ['class' => 'btn btn-warning ', 'style' => 'margin:3px']);
        }
        if (!empty($model->transmittal->transmittal_id)) {
            $transmittal_link = yii::$app->request->baseUrl . "/index.php?r=transmittal/view&id={$model->transmittal->transmittal_id}";
            echo  Html::a('Transmittal ', $transmittal_link, ['class' => 'btn btn-link ', 'style' => 'margin:3px']);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'book.name',
            'reporting_period',
            'mode_of_payment',
            'dvAucs.dv_number',
            'dvAucs.payee.account_name',
            'dvAucs.particular',
            'check_or_ada_no',
            'ada_number',

            [
                'label' => 'Cancelled',
                'value' => function ($model) {
                    if ($model->is_cancelled === 0) {
                        return 'False';
                    } else {
                        return 'True';
                    }
                }
            ],
            'issuance_date',
            [
                'attribute' => 'begin_time',
                'value' => function ($model) {
                    return date('h:i A', strtotime($model->begin_time));
                }
            ],
            [
                'attribute' => 'out_time',
                'value' => function ($model) {
                    return date('h:i A', strtotime($model->out_time));
                }
            ],

        ],
    ]) ?>


    <div class="container">


        <table class="table table striped">

            <thead></thead>
            <tbody>


            </tbody>
        </table>
    </div>

</div>


<?php
SweetAlertAsset::register($this);
$script = <<<JS
    $("#cancel").click(function(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: true
         },
         function(isConfirm){

           if (isConfirm){
                    $.ajax({
                        type:"POST",
                        url:window.location.pathname + "?r=cash-disbursement/cancel",
                        data:{
                            id:$("#cancel_id").val()
                        },
                        success:function(data){
                            var res = JSON.parse(data)
                            var cancelled = res.cancelled?"Successfuly Cancelled":"Successfuly Activated";
                            if(res.isSuccess){
                                swal({
                                        title:cancelled,
                                        type:'success',
                                        button:false,
                                        timer:3000,
                                    },function(){
                                        location.reload(true)
                                    })
                            }
                            else{
                                swal({
                                        title:"Error Cannot Cancel",
                                        text:"Dili Ma  Cancel ang Disbursment Niya",
                                        type:'error',
                                        button:false,
                                        timer:3000,
                                    })
                            }

                        }
                    })


            } 
        })

    

    })
JS;
$this->registerJs($script);
?>