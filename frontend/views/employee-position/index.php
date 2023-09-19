<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeePositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employee Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-position-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=employee-position/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>'primary',
            'heading'=>'Employee Positions'
        ],
        'columns' => [

            'id',
            'position',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php
$script = <<<JS

        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

  
             
        
JS;
$this->registerJs($script);
?>