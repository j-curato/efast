<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_request_item}}`.
 */
class m220110_054515_create_pr_purchase_request_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_request_item}}', [
            'id' => $this->primaryKey(),
            'pr_purchase_request_id'=>$this->integer(),
            'pr_stock_id'=>$this->integer(),
            'quantity'=>$this->integer(),
            'unit_cost'=>$this->decimal(10,2),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_purchase_request_item}}');
    }
}
