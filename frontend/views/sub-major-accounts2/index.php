<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubMajorAccounts2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Major Accounts2s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-major-accounts2-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sub Major Accounts2', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'object_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
