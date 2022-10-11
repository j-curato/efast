<?php

use yii\db\Migration;

/**
 * Class m221011_070547_add_sub_accounts_in_other_prroperty_details_table
 */
class m221011_070547_add_sub_accounts_in_other_prroperty_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('other_property_details', 'fk_sub_account1_id', $this->integer());
        $this->addColumn('other_property_details', 'fk_depreciation_sub_account1_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('other_property_details', 'fk_sub_account1_id');
        $this->dropColumn('other_property_details', 'fk_depreciation_sub_account1_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221011_070547_add_sub_accounts_in_other_prroperty_details_table cannot be reverted.\n";

        return false;
    }
    */
}
