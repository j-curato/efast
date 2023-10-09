<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notice_of_postponement}}`.
 */
class m231005_022748_create_notice_of_postponement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notice_of_postponement}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->unique()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('notice_of_postponement', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notice_of_postponement}}');
    }
}
