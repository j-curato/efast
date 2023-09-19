<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HolidaysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Holidays';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holidays-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=holidays/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Holidays'
        ],
        'columns' => [

            'name',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<?php
SweetAlertAsset::register($this);

$script = <<<JS
            var i=false;
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