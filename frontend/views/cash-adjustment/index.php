<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashAdjustmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Adjustments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-adjustment-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=cash-adjustment/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Book',
                'value' => function ($model) {
                    return $model->books->name;
                }
            ],
            'particular:ntext',
            'date',
            'reporting_period',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
<script>
    $('#modalButtoncreate').click(function() {
        $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
    });
    $('a[title=Update]').click(function(e) {
        e.preventDefault();

        $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
    });
</script>