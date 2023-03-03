<?php

use yii\db\Migration;

/**
 * Class m230228_021441_add_is_unservicesable_in_par_table
 */
class m230228_021441_add_is_unservicesable_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'is_unserviceable', $this->boolean()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('par', 'is_unserviceable');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_021441_add_is_unservicesable_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
