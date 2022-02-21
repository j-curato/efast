<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jev_beginning_balance_item}}`.
 */
class m220120_082523_create_jev_beginning_balance_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jev_beginning_balance_item}}', [
            'id' => $this->primaryKey(),
            'jev_beginning_balance_id'=>$this->integer(),
            'object_code'=>$this->string(),
            'debit'=>$this->decimal(12,2)->defaultValue(0),
            'credit'=>$this->decimal(12,2)->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jev_beginning_balance_item}}');
    }
}
