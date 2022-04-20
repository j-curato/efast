<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PAR';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="par-index">


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=par/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'PAR'
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'columns' => [

            'par_number',

            [
                'label' => 'Property',
                'attribute' => 'fk_property_id',
                'value' => function ($model) {
                    $property = '';
                    if (!empty($model->property->property_number) && !empty($model->property->article)) {
                        $property = $model->property->property_number . ' - ' . $model->property->article;
                    }
                    return $property;
                }
            ],

            'date',
            [
                'label' => 'Recieved By',
                'attribute' => 'employee_id',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->employee->f_name)) {
                        $f_name = !empty($model->employee->f_name) ? $model->employee->f_name : '';
                        $m_name = !empty($model->employee->m_name[0]) ? $model->employee->m_name[0] : '';
                        $l_name = !empty($model->employee->l_name) ? $model->employee->l_name : '';
                        $emp =   $f_name . ' ' .  $m_name . '. ' .  $l_name;
                    }
                    return $emp;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php
$script = <<<JS
            var i=false;

        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
        
JS;
$this->registerJs($script);
?>