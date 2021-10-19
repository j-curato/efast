<?php

use yii\db\Migration;

/**
 * Class m211019_010923_add_document_link_in_cibr
 */
class m211019_010923_add_document_link_in_cibr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cibr','document_link',$this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cibr','document_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211019_010923_add_document_link_in_cibr cannot be reverted.\n";

        return false;
    }
    */
}
