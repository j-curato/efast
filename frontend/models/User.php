<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 * @property string|null $province
 * @property string|null $division
 * @property int|null $fk_employee_id
 * @property int|null $fk_office_id
 * @property int|null $fk_division_id
 * @property int|null $fk_division_program_unit
 *
 * @property PrPurchaseRequest[] $prPurchaseRequests
 * @property RequestForInspection[] $requestForInspections
 * @property SupplementalPpmp[] $supplementalPpmps
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['id', 'status', 'created_at', 'updated_at', 'fk_employee_id', 'fk_office_id', 'fk_division_id', 'fk_division_program_unit'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'province'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['division'], 'string', 'max' => 50],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['id'], 'unique'],

        ];
    }
    public function getRole()
    {
        $auth = Yii::$app->authManager;
        foreach ($auth->getRolesByUser($this->id) as $key => $role) {
            if ($key !== 'guest') {
                return $key;
            }
        }
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'province' => 'Province',
            'division' => 'Division',
            'fk_employee_id' => 'Fk Employee ID',
            'fk_office_id' => 'Fk Office ID',
            'fk_division_id' => 'Fk Division ID',
            'fk_division_program_unit' => 'Fk Division Program Unit',
        ];
    }

    /**
     * Gets query for [[PrPurchaseRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrPurchaseRequests()
    {
        return $this->hasMany(PrPurchaseRequest::class, ['fk_created_by' => 'id']);
    }

    /**
     * Gets query for [[RequestForInspections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestForInspections()
    {
        return $this->hasMany(RequestForInspection::class, ['fk_created_by' => 'id']);
    }

    /**
     * Gets query for [[SupplementalPpmps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplementalPpmps()
    {
        return $this->hasMany(SupplementalPpmp::class, ['fk_created_by' => 'id']);
    }
}
