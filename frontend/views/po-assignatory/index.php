<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoAsignatorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Asignatories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-asignatory-index">


    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=po-assignatory/create'),
            'id' => 'modalButtoncreate', 'class' => 'btn btn-success',
            'data-placement' => 'left',
            'data-toggle' => 'tooltip',
            'title' => 'Add Sector'
        ]); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Asignatory'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<?php
$script = <<<JS
    $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
JS;
$this->registerJs($script);
?>