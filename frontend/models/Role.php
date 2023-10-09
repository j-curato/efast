<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property Role[] $children
 * @property Role[] $parents
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->type)) {
                    $this->type = 1;
                }
            }
        }
    }
    public function getPermissions()
    {
        return Yii::$app->authManager->getPermissionsByRole($this->name);
        
    }
    public function getChildren()
    {
        return Yii::$app->authManager->getChildren($this->name);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    // /**
    //  * Gets query for [[RuleName]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getRuleName()
    // {
    //     return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    // }

    // /**
    //  * Gets query for [[AuthItemChildren]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getAuthItemChildren()
    // {
    //     return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    // }

    // /**
    //  * Gets query for [[AuthItemChildren0]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getAuthItemChildren0()
    // {
    //     return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    // }

    // /**
    //  * Gets query for [[Children]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getChildren()
    // {
    //     return $this->hasMany(Role::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    // }

    // /**
    //  * Gets query for [[Parents]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getParents()
    // {
    //     return $this->hasMany(Role::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    // }
}
