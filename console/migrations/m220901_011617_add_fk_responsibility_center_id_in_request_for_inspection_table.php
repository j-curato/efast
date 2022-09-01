<?php

use yii\db\Migration;

/**
 * Class m220901_011617_add_fk_responsibility_center_id_in_request_for_inspection_table
 */
class m220901_011617_add_fk_responsibility_center_id_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection', 'fk_responsibility_center_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request_for_inspection', 'fk_responsibility_center_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220901_011617_add_fk_responsibility_center_id_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
