<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit_of_measure}}`.
 */
class m211025_020936_create_unit_of_measure_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unit_of_measure}}', [
            'id' => $this->primaryKey(),
            'unit_of_measure'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unit_of_measure}}');
    }
}
