<?php

use yii\db\Migration;

/**
 * Class m210131_140421_create_table_fund_source
 */
class m210131_140421_create_table_fund_source extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fund_source}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
           
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fund_source}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_140421_create_table_fund_source cannot be reverted.\n";

        return false;
    }
    */
}
