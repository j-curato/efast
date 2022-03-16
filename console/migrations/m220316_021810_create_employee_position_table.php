<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_position}}`.
 */
class m220316_021810_create_employee_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_position}}', [
            'id' => $this->primaryKey(),
            'position'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_position}}');
    }
}
