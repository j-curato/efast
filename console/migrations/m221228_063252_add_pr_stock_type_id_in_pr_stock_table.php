<?php

use yii\db\Migration;

/**
 * Class m221228_063252_add_pr_stock_type_id_in_pr_stock_table
 */
class m221228_063252_add_pr_stock_type_id_in_pr_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_stock', 'pr_stock_type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_stock', 'pr_stock_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221228_063252_add_pr_stock_type_id_in_pr_stock_table cannot be reverted.\n";

        return false;
    }
    */
}
