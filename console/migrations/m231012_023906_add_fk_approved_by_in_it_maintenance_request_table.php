<?php

use yii\db\Migration;

/**
 * Class m231012_023906_add_fk_approved_by_in_it_maintenance_request_table
 */
class m231012_023906_add_fk_approved_by_in_it_maintenance_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->query();
        $this->addColumn('it_maintenance_request', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-it-maintenance-fk_approved_by', 'it_maintenance_request', 'fk_approved_by');
        $this->addForeignKey('fk-it-maintenance-fk_approved_by', 'it_maintenance_request', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-it-maintenance-fk_approved_by', 'it_maintenance_request');
        $this->dropIndex('idx-it-maintenance-fk_approved_by', 'it_maintenance_request');
        $this->dropColumn('it_maintenance_request', 'fk_approved_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231012_023906_add_fk_approved_by_in_it_maintenance_request_table cannot be reverted.\n";

        return false;
    }
    */
}
