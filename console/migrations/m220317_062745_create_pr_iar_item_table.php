<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_iar_item}}`.
 */
class m220317_062745_create_pr_iar_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_iar_item}}', [
            'id' => $this->primaryKey(),
            'fk_pr_iar_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->defaultValue(0),
            'fk_pr_aoq_entry_id' => $this->integer()

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_iar_item}}');
    }
}
