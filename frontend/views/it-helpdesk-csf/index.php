<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItHelpdeskCsfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'It Helpdesk Csfs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="it-helpdesk-csf-index">


    <p>
        <?= Html::a('Create It Helpdesk Csf', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'CSF'
        ],
        'columns' => [

            'id',
            'serial_number',
            'fk_it_maintenance_request',
            'fk_client_id',
            'contact_num',
            //'address:ntext',
            //'email:ntext',
            //'date',
            //'clarity',
            //'skills',
            //'professionalism',
            //'courtesy',
            //'response_time:datetime',
            //'sex',
            //'age_group',
            //'comment:ntext',
            //'vd_reason:ntext',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>