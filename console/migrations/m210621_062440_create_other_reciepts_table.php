<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%other_reciepts}}`.
 */
class m210621_062440_create_other_reciepts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%other_reciepts}}', [
            'id' => $this->primaryKey(),
            'report' => $this->string(50),
            'province' => $this->string(50),
            'fund_source' => $this->text(),
            'advance_type' => $this->string(100),
            'object_code' => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%other_reciepts}}');
    }
}
