<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_rfq_item}}`.
 */
class m220114_023736_create_pr_rfq_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_rfq_item}}', [
            'id' => $this->primaryKey(),
            'pr_rfq_id'=>$this->bigInteger(),
            'pr_purchase_request_item_id'=>$this->integer(),
            
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_rfq_item}}');
    }
}
