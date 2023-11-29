<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_bank_deposit_types}}`.
 */
class m231123_031821_create_tbl_fmi_bank_deposit_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_bank_deposit_types}}', [
            'id' => $this->primaryKey(),
            'deposit_type' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tbl_fmi_bank_deposit_types}}');
    }
}
