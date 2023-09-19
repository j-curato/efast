<?php

use app\models\ProcessOrs;
use app\models\ProcessOrsEntries;
use app\models\ProcessOrsNewView;
use app\models\Raouds;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrsEntries */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Process Ors Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="process-ors-entries-view">
    <?php
    $ors  = $model;
    $entries = ProcessOrsNewView::find()->where('id = :id', ['id' => $model->id])->all();
    ?>
    <h3><?= Html::encode($this->title) ?></h3>

    <div class="container">
        <p>
            <?= Html::a('Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php
            if ($ors->is_cancelled) {
                echo "
                <button class='btn btn-success' id='cancel'>
                    Activate
                </button>
                ";
            } else {
                echo "
                <button class='btn btn-danger' id='cancel'>
                    Cancel
                </button>
                ";
            }
            echo "<input type='text' id='cancel_id' value='$ors->id' style='display:none'/>";
            $t = yii::$app->request->baseUrl . "/index.php?r=transaction/view&id=$ors->transaction_id";
            echo  Html::a('Transaction', $t, ['class' => 'btn btn-info']);
            $adjust = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/re-align&id=$model->id";
            echo Html::a('Adjust/Re-align', $adjust, ['class' => 'btn btn-warning ', 'style' => 'margin:5px']);
            ?>
        </p>
        <table class="table table-striped">

            <thead>
                <th>
                    ID
                </th>
                <th>
                    Reporting Period
                </th>
                <th>
                    Allotment Number
                </th>
                <th>
                    Payee
                </th>
                <th>
                    Particular
                </th>
                <th>
                    Allotment UACS Code
                </th>
                <th>
                    Allotment General Ledger
                </th>
                <th>
                    UACS
                </th>
                <th>
                    General Ledger
                </th>
                <th style='text-align:right'>
                    Amount
                </th>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($entries as $key => $val) {
                    $count = $key + 1;
                    echo "
                    <tr>
                        <td>
                           {$count}
                        </td>
                        <td>
                           {$val->reporting_period}
                        </td>
                        <td>
                           {$val->reporting_period}
                        </td>
                        <td>
                           {$val->payee}
                        </td>
                        <td>
                           {$val->particular}
                        </td>
                        <td>
                           {$val->allotment_uacs}
                        </td>
                      
                        <td>
                           {$val->allotment_account_title}
                        </td>
                      
                        <td>
                           {$val->ors_uacs}
                        </td>
                      
                        <td>
                           {$val->ors_account_title}
                        </td>
                      
                        <td class='amount'>" . number_format($val->amount, 2) . "
                           
                        </td>
                      
         
                    </tr>
                    
                    ";
                    $total += $val->amount;
                }

                ?>
                <tr>
                    <td colspan="9" style='text-align:center;font-weight:bold;'>Total</td>
                    <td style='text-align:right;font-weight:bold;'><?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container">


        <h4>List of DV's Using This ORS</h4>
        <table class="table">
            <thead>
                <th>
                    DV Number
                </th>
                <th>
                    Link
                </th>
            </thead>
            <tbody>

                <?php
                if (!empty($ors->dvAucsEntries)) {
                    $dv_id = 0;
                    foreach ($ors->dvAucsEntries as $val) {
                        if (intval($val->is_deleted) === 0) {

                            $x = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/view&id={$val->dvAucs->id}";
                            echo "<tr>
                        <td>{$val->dvAucs->dv_number}</td>
                        <td>" .
                                Html::a('Dv Link', $x, ['class' => 'btn-xs btn-danger '])
                                . "</td>
                        </tr>";
                        }
                    }

                    // http://10.20.17.33/dti-afms-2/frontend/web/index.php?r=dv-aucs%2Fview&id=6878
                    // echo  
                }
                ?>
            </tbody>

        </table>
    </div>

</div>

<style>
    .container {
        background-color: white;
        padding: 12px;
    }

    .amount {
        text-align: right;
    }
</style>
<?php

SweetAlertAsset::register($this);
$script = <<< JS

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
                    url:window.location.pathname + "?r=process-ors-entries/cancel",
                    data:{
                        id:$("#cancel_id").val()
                    },
                    success:function(data){
                        var res = JSON.parse(data)
                        // swal({
                        //     title:"Success",
                        //     type:'success',
                        //     button:false,
                        //     timer:3000,
                        //  })
                        console.log(data)
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
                        }else{
                            swal({
                                title:"Error",
                                text:res.error,
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
    $(document).ready(function(){
        
    })

JS;
$this->registerJs($script);

?>