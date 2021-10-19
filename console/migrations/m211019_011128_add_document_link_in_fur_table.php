<?php

use yii\db\Migration;

/**
 * Class m211019_011128_add_document_link_in_fur_table
 */
class m211019_011128_add_document_link_in_fur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('fur','document_link',$this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('fur','documet_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211019_011128_add_document_link_in_fur_table cannot be reverted.\n";

        return false;
    }
    */
}
