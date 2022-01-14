<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BacCompositionMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bac Composition Members';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-composition-member-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bac Composition Member', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'bac_composition_id',
            'employee_id',
            'bac_position_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
