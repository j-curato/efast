<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_request_item_specification}}`.
 */
class m220110_055137_create_pr_purchase_request_item_specification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_request_item_specification}}', [
            'id' => $this->primaryKey(),
            'pr_purchase_request_item_id' => $this->integer(),
            'description' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_purchase_request_item_specification}}');
    }
}
