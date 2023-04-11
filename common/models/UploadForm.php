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
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf,xlsx,xls'],
        ];
    }
    public function upload($path, $file_name = 'dv')
    {

        if ($this->validate()) {

            // $newFileName = \Yii::$app->security->generateRandomString(8) . '.' . $this->file->extension;
            $file_name = str_replace(' ', '_', $file_name);
            $newFileName = $file_name . '.' . $this->file->extension;
            $file_path = $path . "\\" . $newFileName;
            $external_file_path = 'E:\scanned_dvs\\' . $newFileName;
            $this->file->saveAs($file_path);
            return $file_path;
            // $this->file->saveAs($file_path);
            // $dropbox_path = "G:\My Drive\qqwer\\" . $newFileName;
            // copy($external_file_path, $dropbox_path);
            // $q = "Z:\Users\USER\Dropbox\scanned_dv\\" . $newFileName;
            // if (!copy($file_path, $dropbox_path)) {
            //     return false;
            // }
            return $newFileName;
        } else {
            return false;
        }
    }
}
