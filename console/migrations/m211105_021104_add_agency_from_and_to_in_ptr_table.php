<?php

use yii\db\Migration;

/**
 * Class m211105_021104_add_agency_from_and_to_in_ptr_table
 */
class m211105_021104_add_agency_from_and_to_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ptr','agency_from_id',$this->integer());
        $this->addColumn('ptr','agency_to_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ptr','agency_from_id');
        $this->dropColumn('ptr','agency_to_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211105_021104_add_agency_from_and_to_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
