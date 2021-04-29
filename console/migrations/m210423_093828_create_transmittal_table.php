<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transmittal}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursement}}`
 */
class m210423_093828_create_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transmittal}}', [
            'id' => $this->primaryKey(),
            'transmittal_number' => $this->string(100),
            'location' => $this->string(20),
            'date' => $this->date(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `cash_disbursement_id`
      
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cash_disbursement}}`


        $this->dropTable('{{%transmittal}}');
    }
}
