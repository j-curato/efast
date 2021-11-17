<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rpcppe}}`.
 */
class m211110_072730_create_rpcppe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rpcppe}}', [
            'rpcppe_number'=>$this->string(),
            'reporting_period'=>$this->string(50),
            'book_id'=>$this->integer(),
            'certified_by'=>$this->string(),
            'approved_by'=>$this->string(),
            'verified_by'=>$this->string(),
            'verified_pos'=>$this->string(),
            'ppe_condition_id'=>$this->integer(),
        ]);

        $this->addPrimaryKey('primary-key-rpcppe_number','rpcppe','rpcppe_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rpcppe}}');
    }
}
