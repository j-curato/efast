<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DivisionProgramUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Division Program Units';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-program-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::button(
            '<i class="glyphicon glyphicon-plus"></i> Create',
            [
                'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=divisions/create'),
                'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' =>
                'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
            ]
        ); ?>

    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary'
        ],
        'columns' => [

            'name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id],['title'=>'Update']);
                }
            ]
        ],
    ]); ?>


</div>
<?php
$js = <<<JS

    $(document).ready(function(){
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });

    })
JS;
$this->registerJs($js);
?>