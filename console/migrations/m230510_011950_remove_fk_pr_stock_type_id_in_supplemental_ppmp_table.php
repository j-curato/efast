<?php

use yii\db\Migration;

/**
 * Class m230510_011950_remove_fk_pr_stock_type_id_in_supplemental_ppmp_table
 */
class m230510_011950_remove_fk_pr_stock_type_id_in_supplemental_ppmp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('supplemental_ppmp', 'fk_pr_stock_type_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('supplemental_ppmp', 'fk_pr_stock_type_id', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230510_011950_remove_fk_pr_stock_type_id_in_supplemental_ppmp_table cannot be reverted.\n";

        return false;
    }
    */
}
