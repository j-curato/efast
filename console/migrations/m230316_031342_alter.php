<?php

use yii\db\Migration;

/**
 * Class m230316_031342_alter
 */
class m230316_031342_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        // drops foreign key for table `{{%par}}`
        $this->dropForeignKey(
            '{{%fk-iirup_items-fk_par_id}}',
            '{{%iirup_items}}'
        );

        // drops index for column `fk_par_id`
        $this->dropIndex(
            '{{%idx-iirup_items-fk_par_id}}',
            '{{%iirup_items}}'
        );
        $this->renameColumn('iirup_items', 'fk_par_id', 'fk_other_property_detail_item_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('iirup_items', 'fk_other_property_detail_item_id', 'fk_par_id');
        // creates index for column `fk_par_id`
        $this->createIndex(
            '{{%idx-iirup_items-fk_par_id}}',
            '{{%iirup_items}}',
            'fk_par_id'
        );

        // add foreign key for table `{{%par}}`
        $this->addForeignKey(
            '{{%fk-iirup_items-fk_par_id}}',
            '{{%iirup_items}}',
            'fk_par_id',
            '{{%par}}',
            'id',
            'CASCADE'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230316_031342_alter cannot be reverted.\n";

        return false;
    }
    */
}
