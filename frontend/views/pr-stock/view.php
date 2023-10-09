<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-stock-view">




    <div class="container card">

        <p>
            <?= Html::button('<i class="glyphicon glyphicon-pencil"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=pr-stock/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
            <?php

            if (Yii::$app->user->can('ro_procurement_admin')) {
                $btn_color = 'btn btn-danger';
                if ($model->is_final) {
                    $btn_color = 'btn btn-success';
                }
                echo "<button type='button' class='$btn_color' id='final'>Final</button>";
            }
            ?>
        </p>
        <table class="table table-striped">




            <?php

            $part = [

                'part-1' => 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES',
                'part-2' => 'PART II. OTHER ITEMS NOT AVALABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES',
                'part-3' => 'PART III - OTHERS OUTSIDE PS'

            ]
            ?>
            <tbody>
                <tr>
                    <th>Stock/Property</th>
                    <td><?= $model->stock_title ?></td>
                </tr>
                <tr>
                    <th>BAC Code</th>
                    <td><?= $model->bac_code ?></td>
                </tr>
                <tr>
                    <th>Unit of Measure</th>
                    <td><?= $model->unitOfMeasure->unit_of_measure ?></td>
                </tr>
                <tr>
                    <th>Chart of Account</th>
                    <td><?php
                        if (!empty($model->chartOfAccount->uacs)) {
                            echo   $model->chartOfAccount->uacs . ' - ' . $model->chartOfAccount->general_ledger;
                        }
                        ?></td>
                </tr>
                <tr>
                    <th>Part</th>
                    <td><?php



                        // $model->part ;
                        echo  $part[$model->part];


                        ?></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td><?= $model->type ?></td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><?= number_format($model->amount, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- <table class="table table-striped">
            <thead>
                <th>
                    <h4>Specifications</h4>
                </th>

            </thead>
            <tbody>

                <?php
                foreach ($model->prStockSpecification as $val) {
                    echo "<tr>
                             <td>$val->description</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table> -->
    </div>

</div>
<style>
    .panel {
        padding: 20px;
    }
</style>
<?php

SweetAlertAsset::register($this);

?>
<script>
    $(document).ready(function() {


        $("#final").click(function(e) {
            e.preventDefault()
            swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Comfirm',
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {

                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: window.location.pathname + "?r=pr-stock/final",
                            data: {
                                id: '<?= $model->id ?>',
                                '_csrf-frontend': '<?php echo Yii::$app->request->csrfToken ?>'
                            },
                            success: function(data) {
                                var res = JSON.parse(data)
                                if (res.isSuccess) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        button: false,
                                        timer: 3000,
                                    }, function() {
                                        location.reload(true)
                                    })
                                } else {
                                    swal({
                                        title: "Error Cannot Cancel",
                                        text: res.error,
                                        type: 'error',
                                        button: false,
                                        timer: 3000,
                                    })
                                }

                            }
                        })


                    }
                })
        })
    })
</script>
<?php
$script = <<<JS
            var i=false;
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

             
        
JS;
$this->registerJs($script);
?>