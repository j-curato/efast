<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%general_ledger}}`.
 */
class m220331_054415_create_general_ledger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%general_ledger}}', [
            'id' => $this->primaryKey(),
            'reporting_period' => $this->string(20)->notNull(),
            'object_code' => $this->string()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%general_ledger}}');
    }
}
