<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_stock_specification}}`.
 */
class m220110_012541_create_pr_stock_specification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_stock_specification}}', [
            'id' => $this->primaryKey(),
            'pr_stock_id'=>$this->integer(),
            'description'=>$this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_stock_specification}}');
    }
}
