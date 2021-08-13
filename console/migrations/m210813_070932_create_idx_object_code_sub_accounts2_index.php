<?php

use yii\db\Migration;

/**
 * Class m210813_070932_create_idx_object_code_sub_accounts2_index
 */
class m210813_070932_create_idx_object_code_sub_accounts2_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_object_code','sub_accounts2','object_code',true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_object_code','sub_accounts2');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210813_070932_create_idx_object_code_sub_accounts2_index cannot be reverted.\n";

        return false;
    }
    */
}
