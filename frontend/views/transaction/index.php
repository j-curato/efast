<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .modal-wide {
        width: 90%;
    }
</style>
<?php


Modal::begin(
    [
        //'header' => '<h2>Create New Region</h2>',
        'id' => 'transactionmodal',
        'size' => 'modal-wide',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]
);
echo "<div class='box box-success' id='modalContent'></div>";
Modal::end();
?>
<div class="transaction-index">


    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Transaction', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=transaction/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'responsibility_center_id',
            'payee_id',
            [
                'label' => 'Payee',
                'attribute' => 'payee.account_name'
            ],
            'particular',
            'gross_amount',
            'tracking_number',
            'earmark_no',
            'payroll_number',
            'transaction_date',
            //'transaction_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php

$js = "
        $('#modalButtoncreate').click(function(){
            $('#transactionmodal').modal('show').find('#modalContent').load($(this).attr('value'));
        }); 
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });


";
$this->registerJs($js, $this::POS_END);
?>