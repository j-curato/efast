<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cibr}}`.
 */
class m210712_014629_create_cibr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cibr}}', [
            'id' => $this->primaryKey(),
            'serial_number'=>$this->string(),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(),
            'book_name'=>$this->string(),
            'is_final'=>$this->boolean()->defaultValue(0),
        ]);
    }

    /**p
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cibr}}');
    }
}
