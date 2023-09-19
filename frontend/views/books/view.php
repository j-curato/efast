<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="books-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'title' => 'Update']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'account_number',
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
    $(document).ready(function(){
        $('a[title=Update]').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
    })
JS;
$this->registerJs($script);

?>