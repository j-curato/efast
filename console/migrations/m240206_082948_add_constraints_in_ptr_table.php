<?php

use yii\db\Migration;

/**
 * Class m240206_082948_add_constraints_in_ptr_table
 */
class m240206_082948_add_constraints_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex("idx-ptr-fk_office_id", 'ptr', 'fk_office_id');
        $this->addForeignKey("fk-ptr-fk_office_id", 'ptr', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-ptr-fk_office_id", 'ptr');
        $this->dropIndex("idx-ptr-fk_office_id", 'ptr');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_082948_add_constraints_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
