<?php

use yii\db\Migration;

/**
 * Class m231012_003353_add_constraints_in_other_property_details_table
 */
class m231012_003353_add_constraints_in_other_property_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-other-pty-dtls-fk_property_id', 'other_property_details', 'fk_property_id');
        $this->addForeignKey('fk-other-pty-dtls-fk_property_id', 'other_property_details', 'fk_property_id', 'property', 'id', 'RESTRICT', 'CASCADE');

        $this->createIndex('idx-other-pty-dtls-fk_sub_account1_id', 'other_property_details', 'fk_sub_account1_id');
        $this->addForeignKey('fk-other-pty-dtls-fk_sub_account1_id', 'other_property_details', 'fk_sub_account1_id', 'sub_accounts1', 'id', 'RESTRICT', 'CASCADE');

        $this->createIndex('idx-other-pty-dtls-fk_depreciation_sub_account1_id', 'other_property_details', 'fk_depreciation_sub_account1_id');
        $this->addForeignKey('fk-other-pty-dtls-fk_depreciation_sub_account1_id', 'other_property_details', 'fk_depreciation_sub_account1_id', 'sub_accounts1', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-other-pty-dtls-fk_property_id', 'other_property_details');
        $this->dropIndex('idx-other-pty-dtls-fk_property_id', 'other_property_details');

        $this->dropForeignKey('fk-other-pty-dtls-fk_sub_account1_id', 'other_property_details');
        $this->dropIndex('idx-other-pty-dtls-fk_sub_account1_id', 'other_property_details');

        $this->dropForeignKey('fk-other-pty-dtls-fk_depreciation_sub_account1_id', 'other_property_details');
        $this->dropIndex('idx-other-pty-dtls-fk_depreciation_sub_account1_id', 'other_property_details');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231012_003353_add_constraints_in_other_property_details_table cannot be reverted.\n";

        return false;
    }
    */
}
