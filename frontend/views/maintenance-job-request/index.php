<?php

use app\components\helpers\MyHelper;
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


    <p>
        <?= Yii::$app->user->can('create_maintenance_job_request') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>


    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',

            'heading' => 'Maintenace Job Requests'
        ],
        'columns' => [


            [
                'attribute' =>  'fk_responsibility_center_id',
                'value' => 'responsibilityCenter.name'
            ],

            [
                'attribute' => 'fk_employee_id',
                'value' => function ($model) {

                    return $model->employee->f_name . ' ' . $model->employee->m_name[0] . '. ' . $model->employee->l_name;
                }
            ],
            'date_requested',
            'problem_description:ntext',
            'recommendation:ntext',
            'action_taken:ntext',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_maintenance_job_request') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],

        ],
    ]); ?>


</div>
<?php
SweetAlertAsset::register($this);

$script = <<<JS
            var i=false;
        $('#mdModal').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

        
  
JS;
$this->registerJs($script);
?>