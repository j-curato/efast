<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cdr_sub_accounts}}`.
 */
class m210621_003539_create_cdr_sub_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cdr_sub_accounts}}', [
            'id' => $this->primaryKey(),
            'object_code' => $this->string(),
            'name' => $this->text(),
            'province'=>$this->string(50)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cdr_sub_accounts}}');
    }
}
