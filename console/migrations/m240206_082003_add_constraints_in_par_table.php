<?php

use yii\db\Migration;

/**
 * Class m240206_082003_add_constraints_in_par_table
 */
class m240206_082003_add_constraints_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex("idx-par-fk_received_by", 'par', 'fk_received_by');
        $this->addForeignKey("fk-par-fk_received_by", 'par', 'fk_received_by', 'employee', 'employee_id', 'RESTRICT');

        $this->createIndex("idx-par-fk_actual_user", 'par', 'fk_actual_user');
        $this->addForeignKey("fk-par-fk_actual_user", 'par', 'fk_actual_user', 'employee', 'employee_id', 'RESTRICT');

        $this->createIndex("idx-par-fk_issued_by_id", 'par', 'fk_issued_by_id');
        $this->addForeignKey("fk-par-fk_issued_by_id", 'par', 'fk_issued_by_id', 'employee', 'employee_id', 'RESTRICT');


        $this->createIndex("idx-par-fk_property_id", 'par', 'fk_property_id');
        $this->addForeignKey("fk-par-fk_property_id", 'par', 'fk_property_id', 'property', 'id', 'RESTRICT');


        $this->createIndex("idx-par-fk_office_id", 'par', 'fk_office_id');
        $this->addForeignKey("fk-par-fk_office_id", 'par', 'fk_office_id', 'office', 'id', 'RESTRICT');


        $this->createIndex("idx-par-fk_ptr_id", 'par', 'fk_ptr_id');
        $this->addForeignKey("fk-par-fk_ptr_id", 'par', 'fk_ptr_id', 'ptr', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-par-fk_received_by", 'par');
        $this->dropIndex("idx-par-fk_received_by", 'par');

        $this->dropForeignKey("fk-par-fk_actual_user", 'par');
        $this->dropIndex("idx-par-fk_actual_user", 'par');

        $this->dropForeignKey("fk-par-fk_issued_by_id", 'par');
        $this->dropIndex("idx-par-fk_issued_by_id", 'par');


        $this->dropForeignKey("fk-par-fk_property_id", 'par');
        $this->dropIndex("idx-par-fk_property_id", 'par');


        $this->dropForeignKey("fk-par-fk_office_id", 'par');
        $this->dropIndex("idx-par-fk_office_id", 'par');


        $this->dropForeignKey("fk-par-fk_ptr_id", 'par');
        $this->dropIndex("idx-par-fk_ptr_id", 'par');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_082003_add_constraints_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
