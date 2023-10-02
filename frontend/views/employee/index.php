<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use app\components\helpers\MyHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">


    <p>
        <?= Html::a(
            '<i class="fa fa-pencil-alt"></i> Create',
            ['create'],
            ['class' => 'btn btn-success modalButtonCreate']
        ); ?>

    </p>




    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Employee'
        ],

        'export' => [
            'fontAwesome' => true
        ],
        'columns' => [

            'f_name',
            'l_name',
            'm_name',
            'status',
            'position',
            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name'

            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->employee_id, 'modalButtonUpdate');
                }
            ],
        ],
    ]); ?>


</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [JqueryAsset::class]]
)
?>
<?php
$script = <<<JS
        //     var i=false;
        // $('#modalButtoncreate').click(function(){
        //     $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        // });
        // $('a[title=Update]').click(function(e){
        //     e.preventDefault();
            
        //     $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        // });
        
JS;
$this->registerJs($script);
?>