<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 */
class m211025_024636_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee}}', [
            'employee_id' => $this->string(),
            'f_name' => $this->string(),
            'l_name' => $this->string(),
            'm_name' => $this->string(),
            'status' => $this->string(),
            'property_custodian' => $this->boolean()->defaultValue(false),
            'position' => $this->string(),
        ]);
        $this->addPrimaryKey('pk-employee-id', 'employee', 'employee_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee}}');
    }
}
