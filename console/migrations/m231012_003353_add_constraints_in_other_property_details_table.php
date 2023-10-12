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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-other-pty-dtls-fk_property_id', 'other_property_details');
        $this->dropIndex('idx-other-pty-dtls-fk_property_id', 'other_property_details');
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
