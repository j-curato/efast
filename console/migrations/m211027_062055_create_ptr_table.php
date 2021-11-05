<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ptr}}`.
 */
class m211027_062055_create_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ptr}}', [
            'ptr_number' => $this->string(),
            'par_number' => $this->string(),
            'transfer_type_id' => $this->integer(),
            'date' => $this->date(),
            'reason' => $this->text(),
            'from' => $this->string(),
            'to' => $this->string()


        ]);
        $this->addPrimaryKey('pk-ptr-number','ptr','ptr_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ptr}}');
    }
}
