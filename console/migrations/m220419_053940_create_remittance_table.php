<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%remittance}}`.
 */
class m220419_053940_create_remittance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%remittance}}', [
            'id' => $this->primaryKey(),
            'remittance_number'=>$this->string()->notNull(),
            'type'=>$this->string()->notNull(),
            'reporting_period'=>$this->string(20)->notNull(),
            'payroll_id'=>$this->bigInteger(),
            'payee_id'=>$this->integer(),
            'book_id'=>$this->integer()->notNull(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('remittance','id',$this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%remittance}}');
    }
}
