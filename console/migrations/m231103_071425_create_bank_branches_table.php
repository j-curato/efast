<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_branches}}`.
 */
class m231103_071425_create_bank_branches_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_branches}}', [
            'id' => $this->primaryKey(),
            'fk_bank_id' => $this->integer()->notNull(),
            'branch_name' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->createIndex('idx-bank_banch->fk_bank_id', 'bank_branches', 'fk_bank_id');
        $this->addForeignKey('fk-bank_banch->fk_bank_id', 'bank_branches', 'fk_bank_id', 'banks', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bank_banch->fk_bank_id', 'bank_branches');
        $this->dropIndex('idx-bank_banch->fk_bank_id', 'bank_branches');
        $this->dropTable('{{%bank_branches}}');
    }
}
