<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%remittance_payee}}`.
 */
class m220407_065120_create_remittance_payee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%remittance_payee}}', [
            'id' => $this->primaryKey(),
            'payee_id' => $this->bigInteger()->notNull(),
            'object_code' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%remittance_payee}}');
    }
}
