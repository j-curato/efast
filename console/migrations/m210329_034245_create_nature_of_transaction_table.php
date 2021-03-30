<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nature_of_transaction}}`.
 */
class m210329_034245_create_nature_of_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nature_of_transaction}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%nature_of_transaction}}');
    }
}
