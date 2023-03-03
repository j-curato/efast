<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SsfSpStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SSF SP Status';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssf-sp-status-index">


    <p>
        <?= Html::a('Create Ssf Sp Status', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary'
        ],
        'columns' => [

            'name',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]

        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);

?>