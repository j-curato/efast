<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadImage is the model behind the upload form.
 */
class UploadImage extends Model
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
            // [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx, xls'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg,png'],
        ];
    }
    public function upload()
    {

        if ($this->validate()) {
            $this->file->saveAs('profile_pics/' . YIi::$app->user->identity->id . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}
