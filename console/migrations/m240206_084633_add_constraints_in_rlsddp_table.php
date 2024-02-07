<?php

use yii\db\Migration;

/**
 * Class m240206_084633_add_constraints_in_rlsddp_table
 */
class m240206_084633_add_constraints_in_rlsddp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex("idx-rlsddp-fk_office_id", 'rlsddp', 'fk_office_id');
        $this->addForeignKey("fk-rlsddp-fk_office_id", 'rlsddp', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-rlsddp-fk_office_id", 'rlsddp');
        $this->dropIndex("idx-rlsddp-fk_office_id", 'rlsddp');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_084633_add_constraints_in_rlsddp_table cannot be reverted.\n";

        return false;
    }
    */
}
