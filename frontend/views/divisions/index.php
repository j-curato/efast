<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DivisionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Divisions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="divisions-index">

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

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Divisions',
        ],
        'columns' => [

            'division',
            [
                'label' => 'Division Chief',
                'attribute' => 'fk_division_chief',
                'value' => function ($model) {
                    return $model->employee->f_name . ' ' . $model->employee->m_name[0] . ' ' . $model->employee->l_name;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
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