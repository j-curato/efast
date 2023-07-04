<?php

use yii\db\Migration;

/**
 * Class m230301_013010_alter_attributes_in_ptr_table
 */
class m230301_013010_alter_attributes_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->dropPrimaryKey('PRIMARY', 'ptr');
        // $this->addPrimaryKey('pk_id', 'ptr', 'id');
        // $this->alterColumn('ptr', 'ptr_number', $this->string()->unique()->notNull()->after('id'));

        // $this->renameColumn('ptr', 'transfer_type_id', 'fk_transfer_type_id');
        // $this->renameColumn('ptr', 'agency_to_id', 'fk_to_agency_id');

        // $this->addColumn('ptr', 'fk_approved_by', $this->bigInteger()->after('ptr_number'));
        // $this->addColumn('ptr', 'fk_received_by', $this->bigInteger()->after('ptr_number'));
        // $this->addColumn('ptr', 'fk_actual_user', $this->bigInteger()->after('ptr_number'));
        // $this->addColumn('ptr', 'fk_issued_by', $this->bigInteger()->after('ptr_number'));
        // $this->addColumn('ptr', 'fk_property_id', $this->bigInteger()->after('ptr_number'));

        // $this->dropColumn('ptr', 'par_number');
        // $this->dropColumn('ptr', 'reason');
        // $this->dropColumn('ptr', 'employee_from');
        // $this->dropColumn('ptr', 'employee_to');
        // $this->dropColumn('ptr', 'fk_par_id');

        // $this->createIndex('idx-fk_approved_by', 'ptr', 'fk_approved_by');
        // $this->createIndex('idx-fk_received_by', 'ptr', 'fk_received_by');
        // $this->createIndex('idx-fk_actual_user', 'ptr', 'fk_actual_user');
        // $this->createIndex('idx-fk_issued_by', 'ptr', 'fk_issued_by');
        // $this->createIndex('idx-fk_transfer_type_id', 'ptr', 'fk_transfer_type_id');
        // $this->createIndex('idx-fk_to_agency_id', 'ptr', 'fk_to_agency_id');
        // $this->createIndex('idx-fk_property_id', 'ptr', 'fk_property_id');

        // $this->addForeignKey('fk-ptr-fk_approved_by', 'ptr', 'fk_approved_by', 'employee', 'employee_id');
        // $this->addForeignKey('fk-ptr-fk_received_by', 'ptr', 'fk_received_by', 'employee', 'employee_id');
        // $this->addForeignKey('fk-ptr-fk_actual_user', 'ptr', 'fk_actual_user', 'employee', 'employee_id');
        // $this->addForeignKey('fk-ptr-fk_issued_by', 'ptr', 'fk_issued_by', 'employee', 'employee_id');
        // $this->addForeignKey('fk-ptr-fk_transfer_type_id', 'ptr', 'fk_transfer_type_id', 'transfer_type', 'id');
        // $this->addForeignKey('fk-ptr-fk_to_agency_id', 'ptr', 'fk_to_agency_id', 'agency', 'id');
        // $this->addForeignKey('fk-ptr-fk_property_id', 'ptr', 'fk_property_id', 'property', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('pk_id', 'ptr');
        $this->dropIndex('ptr_number', 'ptr');
        $this->addPrimaryKey('pk_ptr_number', 'ptr', 'ptr_number');


        $this->dropForeignKey('fk-ptr-fk_approved_by', 'ptr');
        $this->dropForeignKey('fk-ptr-fk_received_by', 'ptr');
        $this->dropForeignKey('fk-ptr-fk_actual_user', 'ptr');
        $this->dropForeignKey('fk-ptr-fk_issued_by', 'ptr');
        $this->dropForeignKey('fk-ptr-fk_transfer_type_id', 'ptr');
        $this->dropForeignKey('fk-ptr-fk_to_agency_id', 'ptr');
        $this->dropForeignKey('fk-ptr-fk_property_id', 'ptr');

        $this->dropIndex('idx-fk_approved_by', 'ptr');
        $this->dropIndex('idx-fk_received_by', 'ptr');
        $this->dropIndex('idx-fk_actual_user', 'ptr');
        $this->dropIndex('idx-fk_issued_by', 'ptr');
        $this->dropIndex('idx-fk_transfer_type_id', 'ptr');
        $this->dropIndex('idx-fk_to_agency_id', 'ptr');
        $this->dropIndex('idx-fk_property_id', 'ptr');

        $this->renameColumn('ptr', 'fk_transfer_type_id', 'transfer_type_id');
        $this->renameColumn('ptr', 'fk_to_agency_id', 'agency_to_id');

        $this->dropColumn('ptr', 'fk_approved_by');
        $this->dropColumn('ptr', 'fk_received_by');
        $this->dropColumn('ptr', 'fk_actual_user');
        $this->dropColumn('ptr', 'fk_issued_by');
        $this->dropColumn('ptr', 'fk_property_id');

        $this->addColumn('ptr', 'par_number', $this->string());
        $this->addColumn('ptr', 'reason', $this->string());
        $this->addColumn('ptr', 'employee_from', $this->string());
        $this->addColumn('ptr', 'employee_to', $this->string());
        $this->addColumn('ptr', 'fk_par_id', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230301_013010_alter_attributes_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
