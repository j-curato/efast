<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->employee_id;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">


    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=employee/update&id=' . $model->employee_id), 'id' => 'mdModal', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'f_name',
            'l_name',
            'm_name',
            'status',
            'position',
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
            var i=false;
        $('#mdModal').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
        
JS;
$this->registerJs($script);
?>