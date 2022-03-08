<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_contract_type}}`.
 */
class m220303_065013_create_pr_contract_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_contract_type}}', [
            'id' => $this->primaryKey(),
            'contract_name'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_contract_type}}');
    }
}
