<?php

use yii\db\Migration;

/**
 * Class m210329_072054_add_mandatory_reserve_id_to_raouds_table
 */
class m210329_072054_add_mandatory_reserve_id_to_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds','mandatory_reserve_id',$this->integer());

        $this->createIndex(
            '{{%idx-raouds-mandatory_reserve_id}}',
            '{{%raouds}}',
            'mandatory_reserve_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-raouds-mandatory_reserve_id}}',
            '{{%raouds}}',
            'mandatory_reserve_id',
            '{{%mandatory_reserve}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-raouds-mandatory_reserve_id}}',
            '{{%raouds}}'
        );

        // drops index for column `mandatory_reserve_id`
        $this->dropIndex(
            '{{%idx-raouds-mandatory_reserve_id}}',
            '{{%raouds}}'
        );
        $this->dropColumn('raouds','mandatory_reserve_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210329_072054_add_mandatory_reserve_id_to_raouds_table cannot be reverted.\n";

        return false;
    }
    */
}
