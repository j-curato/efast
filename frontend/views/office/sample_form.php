<?php

use app\models\Office;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


$this->title = 'Batch Create Items';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/vue.js", ['position' => $this::POS_HEAD]);
$index = 0;
?>

<div class="item-batch-create">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin(); ?>

    <div id="app">
        <div v-for="(item, index) in items" :key="index" class="form-row">
            <?= $form->field(new Office(), "id")->textInput(['v-model' => "item.id", 'placeholder' => 'Item Name', ':name' => "'Office[' + index + '][id]'", ':id' => "'office-' + index + '-id'"]) ?>
            <?= $form->field(new Office(), "office_name")->textInput(['v-model' => "item.office_name", 'placeholder' => 'Item Name', ':name' => "'Office[' + index + '][office_name]'", ':id' => "'office-' + index + '-office_name'"]) ?>
            <button @click="removeItem(index)" type="button">Remove</button>

        </div>
        <button @click="addItem" type="button">Add Item</button>
    </div>
    <!-- <?php foreach ($items as $index => $item) : ?>

        <?= $form->field($item, "[$index]id")->textInput() ?>
        <?= $form->field($item, "[$index]office_name")->textInput() ?>
        </tr>
    <?php endforeach; ?> -->
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script>
    new Vue({
        el: '#app',
        data: {
            items: <?= json_encode($items) ?>,
        },

        methods: {
            addItem() {
                console.log(this.items)
                this.items.push(<?= json_encode(new Office()) ?>);
            },
            removeItem(index) {
                this.items.splice(index, 1);
            }
        }
    });
</script>