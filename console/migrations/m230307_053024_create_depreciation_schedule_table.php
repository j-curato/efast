<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%depreciation_schedule}}`.
 */
class m230307_053024_create_depreciation_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%depreciation_schedule}}', [
            'id' => $this->primaryKey(),
            'reporting_period' => $this->string()->notNull(),
            'fk_book_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%depreciation_schedule}}');
    }
}
