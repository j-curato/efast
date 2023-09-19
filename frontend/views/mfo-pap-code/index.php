<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MfoPapCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MFO/PAP Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mfo-pap-code-index">


    <p>
        <?= Html::a('Create Mfo Pap Code', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of MFO/PAP Codes'
        ],
        'columns' => [

            'code',
            'name',
            'description',
            'division',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>