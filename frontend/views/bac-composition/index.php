<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BacCompositionnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bac Compositions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-composition-index">


    <p>
        <?= Html::a('Create Bac Composition', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'BAC Composition'
        ],
        'columns' => [

            'id',
            'effectivity_date',
            'expiration_date',
            'rso_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>