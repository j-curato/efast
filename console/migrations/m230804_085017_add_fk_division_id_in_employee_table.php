<?php

use yii\db\Migration;

/**
 * Class m230804_085017_add_fk_division_id_in_employee_table
 */
class m230804_085017_add_fk_division_id_in_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee', 'fk_division_id', $this->bigInteger());
        $this->createIndex('idx-emp-fk_division_id', 'employee', 'fk_division_id');
        $this->addForeignKey('fk-emp-fk_division_id', 'employee', 'fk_division_id', 'divisions', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-emp-fk_division_id', 'employee');
        $this->dropIndex('idx-emp-fk_division_id', 'employee');
        $this->dropColumn('employee', 'fk_division_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230804_085017_add_fk_division_id_in_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
