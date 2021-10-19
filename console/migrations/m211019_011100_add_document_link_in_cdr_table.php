<?php

use yii\db\Migration;

/**
 * Class m211019_011100_add_document_link_in_cdr_table
 */
class m211019_011100_add_document_link_in_cdr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cdr','document_link',$this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cdr','document_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211019_011100_add_document_link_in_cdr_table cannot be reverted.\n";

        return false;
    }
    */
}
