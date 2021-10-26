<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property}}`.
 */
class m211025_021250_create_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property}}', [
            'property_number' => $this->string(),
            'book_id' => $this->integer(),
            'unit_of_measure_id' => $this->integer(),
            'employee_id' => $this->string(),
            'iar_number' => $this->string(),
            'article' => $this->string(),
            'model' => $this->string(),
            'serial_number' => $this->string(),
            'quantity' => $this->integer(),
            'date' => $this->date(),
            'acquisition_amount' => $this->decimal(10, 2)
        ]);
        $this->addPrimaryKey('property-number', 'property', 'property_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property}}');
    }
}
