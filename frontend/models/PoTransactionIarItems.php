<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transaction_iar_items".
 *
 * @property int $id
 * @property int $fk_po_transaction_id
 * @property int $fk_iar_id
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property Iar $fkIar
 * @property PoTransaction $fkPoTransaction
 */
class PoTransactionIarItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transaction_iar_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_po_transaction_id', 'fk_iar_id'], 'required'],
            [['fk_po_transaction_id', 'fk_iar_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_iar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iar::className(), 'targetAttribute' => ['fk_iar_id' => 'id']],
            [['fk_po_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransaction::className(), 'targetAttribute' => ['fk_po_transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_po_transaction_id' => 'Fk Po Transaction ID',
            'fk_iar_id' => 'Fk Iar ID',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkIar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkIar()
    {
        return $this->hasOne(Iar::className(), ['id' => 'fk_iar_id']);
    }

    /**
     * Gets query for [[FkPoTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPoTransaction()
    {
        return $this->hasOne(PoTransaction::className(), ['id' => 'fk_po_transaction_id']);
    }
}
