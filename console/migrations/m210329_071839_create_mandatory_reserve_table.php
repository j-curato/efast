<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mandatory_reserve}}`.
 */
class m210329_071839_create_mandatory_reserve_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mandatory_reserve}}', [
            'id' => $this->primaryKey(),
            'serial_number'=>$this->string(100),
            'reporting_period'=>$this->string(40),
            'particular'=>$this->text(),
            

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mandatory_reserve}}');
    }
}
