<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DivisionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Divisions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="divisions-index">


    <p>
        <?= Yii::$app->user->can('create_division') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Divisions',
        ],
        'columns' => [

            'division',
            [
                'label' => 'Division Chief',
                'attribute' => 'fk_division_chief',
                'value' => function ($model) {
                    return $model->employee->f_name . ' ' . $model->employee->m_name[0] . ' ' . $model->employee->l_name;
                }
            ],


            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_division') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
              
                }
            ]
        ],
    ]); ?>


</div>

<?php

?>