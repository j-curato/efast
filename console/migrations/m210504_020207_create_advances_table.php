<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advances}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursement}}`
 * - `{{%sub_accounts1}}`
 */
class m210504_020207_create_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%advances}}', [
            'id' => $this->primaryKey(),
            
            'province'=>$this->string(50),
            'report_type'=>$this->string(50),
            'particular'=>$this->string(500),
            'nft_number'=>$this->string()
        ]);



  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {



        $this->dropTable('{{%advances}}');
    }
}
