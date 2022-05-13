<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_stock_type}}`.
 */
class m220513_060159_create_pr_stock_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_stock_type}}', [
            'id' => $this->primaryKey(),
            'part'=>$this->string(),
            'type'=>$this->text(),
            'fk_chart_of_account_id'=>$this->bigInteger(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_stock_type}}');
    }
}
