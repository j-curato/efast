<?php

use yii\db\Migration;

/**
 * Class m230328_024526_add_fk_office_id_on_employee_table
 */
class m230328_024526_add_fk_office_id_on_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee', 'fk_office_id', $this->integer());
        $this->createIndex('idx-emp-fk_office_id', 'employee', 'fk_office_id');
        $this->addForeignKey('fk-emp-fk_office_id', 'employee', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-emp-fk_office_id', 'employee');
        $this->dropIndex('idx-emp-fk_office_id', 'employee');
        $this->dropColumn('employee', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230328_024526_add_fk_office_id_on_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
