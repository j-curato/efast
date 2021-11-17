<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inventory_report}}`.
 */
class m211112_011354_create_inventory_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inventory_report}}', [
            'id' => $this->primaryKey(),
            'date'=>$this->date(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%inventory_report}}');
    }
}
