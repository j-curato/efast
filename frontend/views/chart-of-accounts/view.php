<?php

use app\models\MajorAccounts;
use app\models\SubAccounts1;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\DetailView;
use aryelds\sweetalert\SweetAlertAsset;
/* @var $this yii\web\View */
/* @var $model app\models\ChartOfAccounts */

$this->title = $model->uacs;
$this->params['breadcrumbs'][] = ['label' => 'Chart Of Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chart-of-accounts-view">




    <div class="container" style="background-color: white;">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>
            <?= Html::a('Create Sub Account 1', ['sub-accounts1/create', 'chartOfAccountId' => $model->id], ['class' => 'btn btn-success modalButtonCreate']) ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,

            'attributes' => [
                'uacs',
                'general_ledger',
            ],
        ]) ?>

        <table class="table table-striped">
            <thead>
                <tr class="info">
                    <th colspan="2" style="text-align: center;">Sub Account 1's</th>
                </tr>
                <th>
                    Object Code
                </th>
                <th>
                    Account Title
                </th>
            </thead>
            <tbody>
                <?php
                $sub_accounts1 = SubAccounts1::find()->joinWith('subAccounts2')->where("chart_of_account_id =:chart_of_account_id", ['chart_of_account_id' => $model->id])->all();
                foreach ($sub_accounts1 as $val) {
                    echo "<tr>
                <td>$val->object_code</td>
                <td>$val->name</td>

                </tr>";
                    foreach ($val->subAccounts2 as $val2) {

                        echo "
                            <tr>
                            <td></td>
                            <td>$val2->object_code</td>
                            <td>$val2->name</td>
                            </tr>
                        ";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>



</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);
// $script = <<<JS
//         $('.modalButtoncreate').click(function(){
//             $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
//         });
//         $('.modalButtonedit').click(function(){
//             $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
//         });

//         $(document).ready(function(){
//             var at =''
//             var id=''
//             $('#save').click(function(){
//              at = document.getElementById('account_title').value
//              id = document.getElementById('chart_id').value
//             console.log (at)
//             $.ajax({
//                 type:'POST',
//                 url:window.location.pathname + '?r=chart-of-accounts/create-sub-account' ,
//                 data:{
//                     account_title:at,
//                     id:id,
//                 },
//                 success:function(data){
//                     console.log(data)
//                     $('#myModal').modal('hide');

//                         swal( {
//                         position: 'top-end',
//                         icon: 'success',
//                         title: " Reporting Period and Fund Cluster Code are Required",
//                         type: "success",
//                         timer:3000,
//                         closeOnConfirm: false,
//                         closeOnCancel: false
//                     })
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