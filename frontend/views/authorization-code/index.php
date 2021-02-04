<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthorizationCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authorization Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authorization-code-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Authorization Code', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['value'=>Url::to(yii::$app->request->baseUrl . '/index.php?r=authorization-code/create'), 'id'=>'modalButtoncreate', 'class' =>'btn btn-success', 'data-placement'=>'left', 'data-toggle'=>'tooltip', 'title'=>'Add Sector']); ?>
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

            // ['class' => 'yii\grid\ActionColumn'],
            [
                'label'=>'Actions', 
                'format'=>'raw',
                'value'=>function($model){
                    $t = yii::$app->request->baseUrl . '/index.php?r=authorization-code/update&id='.$model->id;
            
                    return ' ' . Html::button('<span class="fa fa-pencil-square-o"></span>', ['value'=>Url::to($t), 'class' =>'btn btn-primary btn-xs modalButtonedit']);
                   
                }
            ]
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