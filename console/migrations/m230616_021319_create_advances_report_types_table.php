<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advances_report_types}}`.
 */
class m230616_021319_create_advances_report_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%advances_report_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advances_report_types}}');
    }
}
