<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_iar}}`.
 */
class m220307_083739_create_pr_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_iar}}', [
            'id' => $this->primaryKey(),
            '_date'=>$this->date()->notNull(),
            'reporting_period'=>$this->string(20)->notNull(),
            'invoice_number'=>$this->string()->notNull(),
            'invoice_date'=>$this->date()->notNull(),
            'fk_pr_purchase_order_id'=>$this->bigInteger()->notNull(),
            'fk_insepection_officer'=>$this->bigInteger()->notNull(),
            'fk_property_custodian'=>$this->bigInteger()->notNull()

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_iar}}');
    }
}
