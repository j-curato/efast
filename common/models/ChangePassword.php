<?php

namespace common\models;

use ErrorException;
use Yii;
use yii\base\Model;
use kartik\password\StrengthValidator;
use lavrentiev\widgets\toastr\Notification;
use yii\bootstrap4\Toast;

/**
 * Change password form
 */
class ChangePassword extends Model
{
    public $old_password;
    public $new_password;
    public $repeat_password;
    public $username;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['old_password', 'new_password', 'repeat_password'], 'required'],
            [['old_password'], 'validateOldPassword'],
            [['new_password'], 'validateNewPassword'],
            [['new_password'], StrengthValidator::class, 'preset' => 'normal', 'min' => 8],
            [['repeat_password'], 'compare', 'compareAttribute' => 'new_password'],
            [['old_password', 'new_password', 'repeat_password'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'old_password' => 'Current Password',
            'new_password' => 'New Password',
            'repeat_password' => 'Confirm New Password',
        ];
    }

    /**
     * Validates the old password.
     * This method serves as the inline validation for old password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOldPassword($attribute, $params)
    {

        if (!$this->hasErrors()) {

            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Incorrect old password.');
            }
        }
    }

    /**
     * Validates the new password.
     * This method serves as the inline validation for new password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateNewPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user && $user->validatePassword($this->new_password)) {
                $this->addError($attribute, 'New password cannot be the same as the old password.');
            }
        }
    }

    /**
     * Updates user's password.
     *
     * @return bool whether the update is successful
     */
    public function updatePassword()
    {

        try {
            $user = $this->getUser();
            $user->setPassword($this->new_password);
            if (!$user->save(false)) {
                throw new ErrorException('Change Pass Failed');
            }
            $this->old_password = '';
            $this->new_password = '';
            $this->repeat_password = '';
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername(Yii::$app->user->identity->username);
        }
        return $this->_user;
    }
    public static function resetPassword($username)
    {
        try {
            $user =  User::findByUsername($username);
            $user->setPassword('abcde54321');
            if (!$user->save(false)) {
                throw new ErrorException('Change Pass Failed');
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
}
