<?php

use yii\db\Migration;

/**
 * Class m210917_025439_add_composite_primary_key_in_fund_source_type_table
 */
class m210917_025439_add_composite_primary_key_in_fund_source_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("ALTER TABLE fund_source_type DROP PRIMARY KEY, ADD PRIMARY KEY(id,`name`)")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("ALTER TABLE fund_source_type DROP PRIMARY KEY, ADD PRIMARY KEY(id)")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210917_025439_add_composite_primary_key_in_fund_source_type_table cannot be reverted.\n";

        return false;
    }
    */
}
