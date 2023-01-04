<?php

use yii\db\Migration;

/**
 * Class m230104_045616_add_fk_approved_by_in_maintenance_job_request_table
 */
class m230104_045616_add_fk_approved_by_in_maintenance_job_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('maintenance_job_request', 'fk_approved_by', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('maintenance_job_request', 'fk_approved_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230104_045616_add_fk_approved_by_in_maintenance_job_request_table cannot be reverted.\n";

        return false;
    }
    */
}
