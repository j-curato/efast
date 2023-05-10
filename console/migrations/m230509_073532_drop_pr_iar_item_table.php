<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%pr_iar_item}}`.
 */
class m230509_073532_drop_pr_iar_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%pr_iar_item}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%pr_iar_item}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
