<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <p>
        <?= Html::a('Create Role', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Roles'
        ],
        'columns' => [

            'name',
            'description:ntext',
            // [
            //     'label' => 'Permissions',
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         $auth = Yii::$app->authManager;
            //         $role = $auth->getRole($model->name);
            //         return json_encode($auth->getPermissionsByRole($model->name));
            //     }
            // ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>