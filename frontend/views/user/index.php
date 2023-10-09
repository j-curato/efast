<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use app\components\helpers\MyHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Users',

        ],
        'columns' => [

            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status === 9 ? 'Inactive' : 'Active';
                }
            ],
            [
                'label' => 'Roles',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getRoles();
                }
            ],

            //'province',
            //'division',
            //'fk_employee_id',
            //'fk_office_id',
            //'fk_division_id',
            //'fk_division_program_unit',

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
<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [JqueryAsset::class]]
)
?>