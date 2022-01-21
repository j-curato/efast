<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jev_beginning_balance}}`.
 */
class m220120_080257_create_jev_beginning_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jev_beginning_balance}}', [
            'id' => $this->primaryKey(),
            'year' => $this->integer(),
            'book_id' => $this->integer(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jev_beginning_balance}}');
    }
}
