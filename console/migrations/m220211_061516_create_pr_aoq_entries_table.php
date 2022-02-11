<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_aoq_entries}}`.
 */
class m220211_061516_create_pr_aoq_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_aoq_entries}}', [
            'id' => $this->primaryKey(),
            'pr_aoq_id' => $this->bigInteger(),
            'payee_id' => $this->integer(),
            'amount' => $this->decimal(10, 2),
            'remark' => $this->text(),
            'is_lowest'=>$this->boolean()->defaultValue(0)

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_aoq_entries}}');
    }
}
