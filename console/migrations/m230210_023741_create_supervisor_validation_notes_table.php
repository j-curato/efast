<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supervisor_validation_notes}}`.
 */
class m230210_023741_create_supervisor_validation_notes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supervisor_validation_notes}}', [
            'id' => $this->primaryKey(),
            'employee_name' => $this->text()->notNull(),
            'evaluation_period' => $this->string()->notNull(),
            'ttl_suc_msr' => $this->string()->notNull(),
            'valid_msr' => $this->string()->notNull(),
            'accomplishments' => $this->string()->notNull(),
            'pgs_rating' => $this->string()->notNull(),
            'comment' => $this->text()->notNull(),

            'passion' => $this->integer()->notNull(),
            'integrety' => $this->integer()->notNull(),
            'competence' => $this->integer()->notNull(),
            'creativity' => $this->integer()->notNull(),
            'synergy' => $this->integer()->notNull(),
            'love_of_country' => $this->integer()->notNull(),

            'int_gbl_olk' => $this->integer()->notNull(),
            'del_solution' => $this->integer()->notNull(),
            'net_link' => $this->integer()->notNull(),
            'del_exl_res' => $this->integer()->notNull(),
            'collaborating' => $this->integer()->notNull(),
            'agility' => $this->integer()->notNull(),
            'proflsm_int' => $this->integer()->notNull(),
            'dev_intervention' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),

        ]);
        $this->alterColumn('supervisor_validation_notes', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supervisor_validation_notes}}');
    }
}
