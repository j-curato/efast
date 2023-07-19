<?php

use yii\db\Migration;

/**
 * Class m230719_005912_update_employee_id_in_user_table
 */
class m230719_005912_update_employee_id_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->renameColumn('user', 'employee_id', 'fk_employee_id');
        $this->alterColumn('user', 'fk_employee_id', $this->bigInteger());
        $this->createIndex('idx-usr-emp_id', 'user', 'fk_employee_id');
        $this->addForeignKey('fk-usr-emp_id', 'user', 'fk_employee_id', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-usr-emp_id', 'user');
        $this->dropIndex('idx-usr-emp_id', 'user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230719_005912_update_employee_id_in_user_table cannot be reverted.\n";

        return false;
    }
    */
}
