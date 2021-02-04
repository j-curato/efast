<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_disbursement}}`.
 */
class m210201_094605_create_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_disbursement}}', [
            'id' => $this->primaryKey(),
            'document_type'=>$this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cash_disbursement}}');
    }
}
