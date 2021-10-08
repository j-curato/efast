<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_adjustment}}`.
 */
class m211008_015603_create_cash_adjustment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_adjustment}}', [
            'id' => $this->primaryKey(),
            'book_id'=>$this->integer(),
            'particular'=>$this->text(),
            'date'=>$this->string(),
            'amount'=>$this->decimal(10,2),
            'reporting_period'=>$this->string(50)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cash_adjustment}}');
    }
}
