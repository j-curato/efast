<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sub-accounts1-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->

            <div class="modal-content">
                <input type="text" hidden value="<?php echo $model->id; ?>" id="chart_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="account_title">Account Title:</label>
                        <input type="account_title" class="form-control" id="account_title">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="save"> Submit</button>
                </div>
            </div>

        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'chart_of_account_id',
            'object_code',
            'name',
        ],
    ]) ?>

</div>

<?php
SweetAlertAsset::register($this);
$script = <<<JS
        $('.mdModal').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });

        $(document).ready(function(){
            var at =''
            var id=''
            $('#save').click(function(){
             at = document.getElementById('account_title').value
             id = document.getElementById('chart_id').value
            console.log (at)
            $.ajax({
                type:'POST',
                url:window.location.pathname + '?r=sub-accounts1/create-sub-account' ,
                data:{
                    account_title:at,
                    id:id,
                },
                success:function(data){
                    console.log(data)
                    $('#myModal').modal('hide');
       
                        swal( {
                        position: 'top-end',
                        icon: 'success',
                        title: " Reporting Period and Fund Cluster Code are Required",
                        type: "success",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
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