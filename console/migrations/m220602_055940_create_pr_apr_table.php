<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_apr}}`.
 */
class m220602_055940_create_pr_apr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_apr}}', [
            'id' => $this->primaryKey(),
            'pr_purchase_request_id' => $this->bigInteger(),
            'apr_number' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_apr}}');
    }
}
