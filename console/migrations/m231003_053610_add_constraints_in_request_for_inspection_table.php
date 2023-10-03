<?php

use yii\db\Migration;

/**
 * Class m231003_053610_add_constraints_in_request_for_inspection_table
 */
class m231003_053610_add_constraints_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex("idx-rfi-fk_chairperson", 'request_for_inspection', 'fk_chairperson');
        $this->addForeignKey("fk-rfi-fk_chairperson", 'request_for_inspection', 'fk_chairperson', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->createIndex("idx-rfi-fk_inspector", 'request_for_inspection', 'fk_inspector');
        $this->addForeignKey("fk-rfi-fk_inspector", 'request_for_inspection', 'fk_inspector', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->createIndex("idx-rfi-fk_property_unit", 'request_for_inspection', 'fk_property_unit');
        $this->addForeignKey("fk-rfi-fk_property_unit", 'request_for_inspection', 'fk_property_unit', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        $this->createIndex("idx-rfi-fk_requested_by", 'request_for_inspection', 'fk_requested_by');
        $this->addForeignKey("fk-rfi-fk_requested_by", 'request_for_inspection', 'fk_requested_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-rfi-fk_chairperson", 'request_for_inspection');
        $this->dropIndex("idx-rfi-fk_chairperson", 'request_for_inspection');

        $this->dropForeignKey("fk-rfi-fk_inspector", 'request_for_inspection');
        $this->dropIndex("idx-rfi-fk_inspector", 'request_for_inspection');

        $this->dropForeignKey("fk-rfi-fk_property_unit", 'request_for_inspection');
        $this->dropIndex("idx-rfi-fk_property_unit", 'request_for_inspection');

        $this->dropForeignKey("fk-rfi-fk_requested_by", 'request_for_inspection');
        $this->dropIndex("idx-rfi-fk_requested_by", 'request_for_inspection');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231003_053610_add_constraints_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
