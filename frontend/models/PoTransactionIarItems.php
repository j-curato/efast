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
            [['fk_iar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iar::class, 'targetAttribute' => ['fk_iar_id' => 'id']],
            [['fk_po_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransaction::class, 'targetAttribute' => ['fk_po_transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_po_transaction_id' => ' PO Transaction ',
            'fk_iar_id' => ' IAR ID',
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
        return $this->hasOne(Iar::class, ['id' => 'fk_iar_id']);
    }

    /**
     * Gets query for [[FkPoTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPoTransaction()
    {
        return $this->hasOne(PoTransaction::class, ['id' => 'fk_po_transaction_id']);
    }
}
