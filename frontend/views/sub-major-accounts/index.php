<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubMajorAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Major Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-major-accounts-index">


    <p>
        <?= Yii::$app->user->can('create_sub_major_account') ? Html::a('Create Sub Major Accounts', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => 'Sub-Major Accounts',
            'type' => 'primary'
        ],
        'columns' => [

            'name',
            'object_code',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_sub_major_account') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [\yii\web\JqueryAsset::class]
    ]
)
?>