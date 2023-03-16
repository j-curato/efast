<?php

use yii\db\Migration;

/**
 * Class m230316_005548_alter_fk_property_status_id_colmn
 */
class m230316_005548_alter_fk_property_status_id_colmn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropForeignKey('fk-rlsddp-fk_property_status_id', 'rlsddp');
        $this->dropIndex('idx-fk_property_status_id', 'rlsddp');
        $this->renameColumn('rlsddp', 'fk_property_status_id', 'status');
        $this->alterColumn('rlsddp', 'blotter_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->renameColumn('rlsddp', 'status', 'fk_property_status_id');
        $this->createIndex('idx-fk_property_status_id', 'rlsddp', 'fk_property_status_id');
        $this->addForeignKey('fk-rlsddp-fk_property_status_id', 'rlsddp', 'fk_property_status_id', 'property_status', 'id', 'RESTRICT', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230316_005548_alter_fk_property_status_id_colmn cannot be reverted.\n";

        return false;
    }
    */
}
