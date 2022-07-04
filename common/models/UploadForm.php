<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx, xls'],
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->file as $f) {
                $f->saveAs('uploads/' . $f->baseName . '.' . $f->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
