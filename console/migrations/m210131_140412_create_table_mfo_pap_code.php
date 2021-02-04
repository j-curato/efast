<?php

use yii\db\Migration;

/**
 * Class m210131_140412_create_table_mfo_pap_code
 */
class m210131_140412_create_table_mfo_pap_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mfo_pap_code}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
           
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mfo_pap_code}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_140412_create_table_mfo_pap_code cannot be reverted.\n";

        return false;
    }
    */
}
