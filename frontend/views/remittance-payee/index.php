<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RemittancePayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remittance Payees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remittance-payee-index">


    <p>
     
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=remittance-payee/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>'primary',
            'heading'=>'Remittance Payees'
        ],
        'columns' => [

            'payee_id',
            'object_code',

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