<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%it_helpdesk_csf}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%it_maintenance_request}}`
 */
class m230623_000656_create_it_helpdesk_csf_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%it_helpdesk_csf}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'fk_it_maintenance_request' => $this->bigInteger()->notNull(),
            'fk_client_id' => $this->bigInteger()->notNull(),
            'contact_num' => $this->string(),
            'address' => $this->text(),
            'email' => $this->text(),
            'date' => $this->date()->notNull(),
            'clarity' => $this->integer()->notNull(),
            'skills' => $this->integer()->notNull(),
            'professionalism' => $this->integer()->notNull(),
            'courtesy' => $this->integer()->notNull(),
            'response_time' => $this->integer()->notNull(),
            'sex' => $this->string(20)->notNull(),
            'age_group' => $this->string()->notNull(),
            'comment' => $this->text(),
            'vd_reason' => $this->text(),
            'social_group' => $this->string(),
            'other_social_group' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->createIndex('idx-it-csf-fk_client_id', 'it_helpdesk_csf', 'fk_client_id');
        $this->addForeignKey('fk-it-csf-fk_client_id', 'it_helpdesk_csf', 'fk_client_id', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');

        // creates index for column `fk_it_maintenance_request`
        $this->createIndex(
            '{{%idx-it_helpdesk_csf-fk_it_maintenance_request}}',
            '{{%it_helpdesk_csf}}',
            'fk_it_maintenance_request'
        );

        // add foreign key for table `{{%it_maintenance_request}}`
        $this->addForeignKey(
            '{{%fk-it_helpdesk_csf-fk_it_maintenance_request}}',
            '{{%it_helpdesk_csf}}',
            'fk_it_maintenance_request',
            '{{%it_maintenance_request}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%it_maintenance_request}}`
        $this->dropForeignKey(
            '{{%fk-it_helpdesk_csf-fk_it_maintenance_request}}',
            '{{%it_helpdesk_csf}}'
        );

        // drops index for column `fk_it_maintenance_request`
        $this->dropIndex(
            '{{%idx-it_helpdesk_csf-fk_it_maintenance_request}}',
            '{{%it_helpdesk_csf}}'
        );
        $this->dropForeignKey('fk-it-csf-fk_client_id', 'it_helpdesk_csf');
        $this->dropIndex('idx-it-csf-fk_client_id', 'it_helpdesk_csf');

        $this->dropTable('{{%it_helpdesk_csf}}');
    }
}
