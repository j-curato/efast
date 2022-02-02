<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%saob}}`.
 */
class m220202_044515_create_saob_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%saob}}', [
            'id' => $this->primaryKey(),
            'from_reporting_period' => $this->string(),
            'to_reporting_period' => $this->string(),
            'mfo_pap_code_id' => $this->integer(),
            'document_recieve_id' => $this->integer(),
            'book_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%saob}}');
    }
}
