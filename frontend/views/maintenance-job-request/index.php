<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MaintenanceJobRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Maintenance Job Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maintenance-job-request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=maintenance-job-request/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>'primary',

            'heading'=>'Maintenace Job Requests'
        ],
        'columns' => [

          
            [
                'attribute'=>  'fk_responsibility_center_id',
                'value'=>'responsibilityCenter.name'
            ],
           
            [
                'attribute'=> 'fk_employee_id',
                'value'=>function($model){

                    return $model->employee->f_name .' ' . $model->employee->m_name[0].'. '. $model->employee->l_name;
                }
            ],
            'date_requested',
            'problem_description:ntext',
            'recommendation:ntext',
            'action_taken:ntext',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
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

        
  
JS;
$this->registerJs($script);
?>