<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inspection_report}}`.
 */
class m220728_062327_create_inspection_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inspection_report}}', [
            'id' => $this->primaryKey(),
            'ir_number' => $this->string()->notNull()->unique(),
            'fk_request_for_inspection_id' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('inspection_report', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%inspection_report}}');
    }
}
