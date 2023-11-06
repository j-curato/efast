<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MajorAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Major Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-accounts-index">


    <p>
        <?= Yii::$app->user->can('create_major_account') ? Html::a('Create Major Accounts', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => 'Major Accounts',
            'type' => 'primary'
        ],
        'columns' => [
            'object_code',
            'name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_major_account') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . $updateBtn;
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