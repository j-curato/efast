<?php

use yii\helpers\Html;
use yii\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;

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
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'chart_of_account_id',
            'object_code',
            'name',
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
                        <input type="text" class="form-control" id="chart_id">
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
              id =  document.getElementById('chart_id').value=$(this).val()
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

    
                    if (res.result=='success'){
   
                        swal( {
                        icon: 'success',
                        title: " Reporting Period and Fund Cluster Code are Required",
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