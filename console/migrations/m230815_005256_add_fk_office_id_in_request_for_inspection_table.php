<?php

use yii\db\Migration;

/**
 * Class m230815_005256_add_fk_office_id_in_request_for_inspection_table
 */
class m230815_005256_add_fk_office_id_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection', 'fk_office_id', $this->integer());
        $this->createIndex('idx-rfi-fk_office_id', 'request_for_inspection', 'fk_office_id');
        $this->addForeignKey('fk-rfi-fk_office_id', 'request_for_inspection', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rfi-fk_office_id', 'request_for_inspection');
        $this->dropIndex('idx-rfi-fk_office_id', 'request_for_inspection');
        $this->dropColumn('request_for_inspection', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230815_005256_add_fk_office_id_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
