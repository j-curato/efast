<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iirup}}`.
 */
class m230315_004625_create_iirup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%iirup}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'fk_acctbl_ofr' => $this->bigInteger()->notNull(),
            'fk_approved_by' => $this->bigInteger()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('iirup', 'id', $this->bigInteger());
        $this->createIndex('idx-fk_acctbl_ofr', 'iirup', 'fk_acctbl_ofr');
        $this->createIndex('idx-fk_approved_by', 'iirup', 'fk_approved_by');
        $this->addForeignKey('fk-iirup-fk_approved_by', 'iirup', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-iirup-fk_acctbl_ofr', 'iirup', 'fk_acctbl_ofr', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-iirup-fk_approved_by', 'iirup');
        $this->dropForeignKey('fk-iirup-fk_acctbl_ofr', 'iirup');
        $this->dropIndex('idx-fk_acctbl_ofr', 'iirup');
        $this->dropIndex('idx-fk_approved_by', 'iirup');
        $this->dropTable('{{%iirup}}');
    }
}
