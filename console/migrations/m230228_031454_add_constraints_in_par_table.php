<?php

use yii\db\Migration;

/**
 * Class m230228_031454_add_constraints_in_par_table
 */
class m230228_031454_add_constraints_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-par_number', 'par', 'par_number', true);
        $this->createIndex('idx-par-fk_recieved_by', 'par', 'fk_recieved_by');
        $this->createIndex('idx-par-fk_actual_user', 'par', 'fk_actual_user');
        $this->createIndex('idx-par-fk_property_id', 'par', 'fk_property_id');
        $this->createIndex('idx-par-fk_issued_by_id', 'par', 'fk_issued_by_id');

        $this->addForeignKey('fk-par-fk_property_id', 'par', 'fk_property_id', 'property', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-par-fk_actual_user', 'par', 'fk_actual_user', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
        // $this->addForeignKey('fk-par-fk_recieved_by', 'par', 'fk_recieved_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
        // $this->addForeignKey('fk-par-fk_issued_by_id', 'par', 'fk_issued_by_id', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-par-fk_property_id', 'par');
        $this->dropForeignKey('fk-par-fk_actual_user', 'par');
        $this->dropIndex('idx-par_number', 'par');
        $this->dropIndex('idx-par-fk_recieved_by', 'par');
        $this->dropIndex('idx-par-fk_actual_user', 'par');
        $this->dropIndex('idx-par-fk_property_id', 'par');
        $this->dropIndex('idx-par-fk_issued_by_id', 'par');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_031454_add_constraints_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
