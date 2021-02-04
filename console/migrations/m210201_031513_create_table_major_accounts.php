<?php

use yii\db\Migration;

/**
 * Class m210201_031513_create_table_major_accounts
 */
class m210201_031513_create_table_major_accounts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%major_accounts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'object_code' => $this->string(20)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%major_accounts}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210201_031513_create_table_major_accounts cannot be reverted.\n";

        return false;
    }
    */
}
