<?php

use yii\db\Migration;

/**
 * Class m220419_083742_remove_property_number_constraint_in_par_table
 */
class m220419_083742_remove_property_number_constraint_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            '{{%fk-par-property_number}}',
            '{{%par}}'
        );

        // drops index for column `property_number`
        $this->dropIndex(
            '{{%idx-par-property_number}}',
            '{{%par}}'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex(
            '{{%idx-par-property_number}}',
            '{{%par}}',
            'property_number'
        );

        // add foreign key for table `{{%property}}`
        $this->addForeignKey(
            '{{%fk-par-property_number}}',
            '{{%par}}',
            'property_number',
            '{{%property}}',
            'property_number',
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_083742_remove_property_number_constraint_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
