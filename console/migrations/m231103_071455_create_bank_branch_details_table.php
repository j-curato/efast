<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_branch_details}}`.
 */
class m231103_071455_create_bank_branch_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_branch_details}}', [
            'id' => $this->primaryKey(),
            'fk_bank_branch_id' => $this->integer()->notNull(),
            'address' => $this->text()->notNull(),
            'bank_manager' => $this->string()->notNull(),
            'is_disabled' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->createIndex('idx-bank_branch_details->fk_bank_branch_id', 'bank_branch_details', 'fk_bank_branch_id');
        $this->addForeignKey('fk-bank_branch_details->fk_bank_branch_id', 'bank_branch_details', 'fk_bank_branch_id', 'bank_branches', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bank_branch_details->fk_bank_branch_id', 'bank_branch_details');
        $this->dropIndex('idx-bank_branch_details->fk_bank_branch_id', 'bank_branch_details');
        $this->dropTable('{{%bank_branch_details}}');
    }
}
