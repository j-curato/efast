<?php

use yii\db\Migration;

/**
 * Class m220905_052317_add_is_deleted_in_ppmp_non_cse_item_categories_table
 */
class m220905_052317_add_is_deleted_in_ppmp_non_cse_item_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ppmp_non_cse_item_categories','is_deleted',$this->boolean()->defaultValue(false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ppmp_non_cse_item_categories','is_deleted');
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220905_052317_add_is_deleted_in_ppmp_non_cse_item_categories_table cannot be reverted.\n";

        return false;
    }
    */
}
