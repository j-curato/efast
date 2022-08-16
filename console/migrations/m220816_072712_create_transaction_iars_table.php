<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction_iars}}`.
 */
class m220816_072712_create_transaction_iars_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction_iars}}', [
            'id' => $this->primaryKey(),
            'fk_transaction_id' => $this->bigInteger(),
            'fk_iar_id' => $this->bigInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaction_iars}}');
    }
}
