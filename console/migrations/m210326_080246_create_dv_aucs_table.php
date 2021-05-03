<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_aucs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%process_ors}}`
 * - `{{%raouds}}`
 */
class m210326_080246_create_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_aucs}}', [
            'id' => $this->primaryKey(),
         
            'dv_number'=>$this->string(),
            'reporting_period'=>$this->string(50),
            'tax_withheld'=>$this->string(),
            'other_trust_liability_withheld'=>$this->string(),
            'net_amount_paid'=>$this->decimal(10,2),
            
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
     
        $this->dropTable('{{%dv_aucs}}');
    }
}
