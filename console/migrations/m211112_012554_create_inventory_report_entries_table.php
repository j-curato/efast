<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inventory_report_entries}}`.
 */
class m211112_012554_create_inventory_report_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inventory_report_entries}}', [
            'id' => $this->primaryKey(),
            'pc_number'=>$this->string(),
            'inventory_report_id'=>$this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%inventory_report_entries}}');
    }
}
