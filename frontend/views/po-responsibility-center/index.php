<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoResponsibilityCenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Responsibility Centers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-responsibility-center-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $display = 'display:none';
        if (Yii::$app->user->can('super-user')) {
            echo Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=po-responsibility-center/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']);
            $display = '';
     
       }
        ?>
    </p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Responsibility Center',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',

            [

                'class' => 'kartik\grid\ActionColumn',

                'updateOptions' => ['style'=>$display],
                'deleteOptions' =>  ['style'=>'display:none'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
        ],
    ]); ?>


</div>

<?php
$script = <<<JS
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