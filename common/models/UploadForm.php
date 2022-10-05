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
            // [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx, xls'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
        ];
    }
    public function upload($path, $file_name = 'dv')
    {

        if ($this->validate()) {

            // $newFileName = \Yii::$app->security->generateRandomString(8) . '.' . $this->file->extension;
            $newFileName = $file_name . '.' . $this->file->extension;
            $this->file->saveAs($path . "\\" . $newFileName);
            // $this->file->saveAs('C:\Users\USER\Desktop\q' . "\\" . $newFileName);
            // system("cmd /c C:\Users\USER\Desktop\cloud_backup_to_dropbox.bat");
            return $newFileName;
        } else {
            return false;
        }
    }
}
