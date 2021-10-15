<?php

use yii\db\Migration;

/**
 * Class m211015_041426_add_document_link_in_liquidation_table
 */
class m211015_041426_add_document_link_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','document_link',$this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','document_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211015_041426_add_document_link_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
