<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_aoq}}`.
 */
class m220211_061108_create_pr_aoq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_aoq}}', [
            'id' => $this->primaryKey(),
            'aoq_number' => $this->string()->unique(),
            'pr_rfq_id' => $this->bigInteger(),
            'pr_date' => $this->date(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->alterColumn('{{%bank_account}}', 'id', $this->bigInteger() . ' NOT NULL ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_aoq}}');
    }
}
