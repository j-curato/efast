<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubAccounts2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Accounts2s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts2-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sub Accounts2', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sub_accounts1_id',
            'object_code',
            'name',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    // $t = yii::$app->request->baseUrl . '/index.php?r=chart-of-accounts/update&id=' .
                    return ' ' . Html::button('<span class="">Add</span>', [
                        'class' => '"btn btn-info btn-xs add-sub',
                        'data-toggle' => "collapse", 'data-target' => "#collapseExample",
                        'aria-expanded'=>"false", 'aria-controls'=>"collapseExample",
                        'value' => $model->id,
                    
                    ]);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <p>
        <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Link with href
        </a>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Button with data-target
        </button>
    </p>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
        </div>
    </div>

</div>