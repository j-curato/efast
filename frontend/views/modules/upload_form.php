<?php


use kartik\widgets\FileInput;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="upload-form">

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <div class='modal-body'>
                    <p>
                    <h4 class="modal-title" id="myModalLabel"><?= $label ?></h4>
                    </p>
                    <?= !empty($templateUrl) ? "<center><a href='$templateUrl'>Download Template Here to avoid error during Upload.</a></center>" : '' ?>
                    <hr>
                    <?php
                    $form = ActiveForm::begin([
                        // 'action' => ['transaction/import-transaction'],
                        // 'method' => 'POST',
                        'id' => 'import',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    // echo '<input type="file">';
                    echo "<br>";
                    echo FileInput::widget([
                        'name' => 'file',
                        // 'options' => ['multiple' => true],
                        'id' => 'fileupload',
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => true,
                        ]
                    ]);
                    ActiveForm::end();
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>



<?php
$csrf = YIi::$app->request->csrfToken;
SweetAlertAsset::register($this);

// $this->registerJsFile('@web/js/vue-spinner.min.js', ['position' => $this::POS_HEAD]);
?>
<script>
    $(document).ready(function() {
        var i = false;
        $('#import').on('beforeSubmit', function(e) {
            e.preventDefault();
            if (!i) {
                i = true;
                $.ajax({
                    url: window.location.pathname + '?r=' + '<?= $url ?>',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        var res = JSON.parse(data)
                        console.log(res)
                        console.log('qwe')
                        if (res.isSuccess) {
                            swal({
                                icon: 'success',
                                title: "Successfuly Imported",
                                type: "success",
                                timer: 3000,
                                closeOnConfirm: false,
                                closeOnCancel: false
                            }, function() {
                                location.reload();
                            })
                        } else {
                            const error_message = res.error_message
                            swal({
                                icon: 'error',
                                title: error_message,
                                type: "error",
                                timer: 10000,
                                closeOnConfirm: false,
                                closeOnCancel: false
                            })
                            i = false;
                        }
                    },
                })

                return false;
            }

        })
        var i = false;
    })
</script>