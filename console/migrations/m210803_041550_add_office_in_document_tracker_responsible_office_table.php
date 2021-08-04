<?php

use yii\db\Migration;

/**
 * Class m210803_041550_add_office_in_document_tracker_responsible_office_table
 */
class m210803_041550_add_office_in_document_tracker_responsible_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document_tracker_responsible_office', 'office', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('document_tracker_responsible_office', 'office');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210803_041550_add_office_in_document_tracker_responsible_office_table cannot be reverted.\n";

        return false;
    }
    */
}
