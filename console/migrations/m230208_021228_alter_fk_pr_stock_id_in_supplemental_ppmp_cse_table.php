<?php

use yii\db\Migration;

/**
 * Class m230208_021228_alter_fk_pr_stock_id_in_supplemental_ppmp_cse_table
 */
class m230208_021228_alter_fk_pr_stock_id_in_supplemental_ppmp_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('supplemental_ppmp_cse', 'fk_pr_stock_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230208_021228_alter_fk_pr_stock_id_in_supplemental_ppmp_cse_table cannot be reverted.\n";

        return false;
    }
    */
}
