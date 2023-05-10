<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%pr_iar}}`.
 */
class m230509_071558_drop_pr_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%pr_iar}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%pr_iar}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
