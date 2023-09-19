<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FinancingSourceCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Financing Source Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financing-source-code-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Add New', ['value'=>Url::to(yii::$app->request->baseUrl . '/index.php?r=financing-source-code/create'), 'id'=>'modalButtoncreate', 'class' =>'btn btn-success', 'data-placement'=>'left', 'data-toggle'=>'tooltip', 'title'=>'Add Sector']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
      ],
       'floatHeaderOptions'=>[
           'top'=>50,
           'position'=>'absolute',
         ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',

            [
                'label'=>'Actions', 
                'format'=>'raw',
                'value'=>function($model){
                    $t = yii::$app->request->baseUrl . '/index.php?r=financing-source-code/update&id='.$model->id;
            
                    return ' ' . Html::button('<span class="fa fa-pencil-square-o"></span>', ['value'=>Url::to($t), 'class' =>'btn btn-primary btn-xs modalButtonedit']);
                   
                }
            ]

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php

$js = "
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });


";
$this->registerJs($js, $this::POS_END);
?>