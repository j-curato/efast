<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MfoPapCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mfo Pap Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mfo-pap-code-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Mfo Pap Code', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code',
            'name',
            'description',
            'division',
            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none']
            ],
        ],
    ]); ?>


</div>
