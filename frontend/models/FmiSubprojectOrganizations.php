<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;

/**
 * This is the model class for table "tbl_fmi_subproject_organizations".
 *
 * @property int $id
 * @property int|null $fk_fmi_subproject_id
 * @property string|null $organization_name
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property FmiSubprojects $fkFmiSubproject
 */
class FmiSubprojectOrganizations extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            'idGenerator' => [
                'class' => GenerateIdBehavior::class
            ],
            'history' => [
                'class' => HistoryLogsBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_subproject_organizations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_fmi_subproject_id', 'is_deleted'], 'integer'],
            [['organization_name'], 'string'],
            [['created_at'], 'safe'],
            [['fk_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_fmi_subproject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_fmi_subproject_id' => 'Fk Fmi Subproject ID',
            'organization_name' => 'Organization Name',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkFmiSubproject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubproject()
    {
        return $this->hasOne(FmiSubprojects::class, ['id' => 'fk_fmi_subproject_id']);
    }
}
