<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_fund_releases}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_subprojects}}`
 * - `{{%tbl_fmi_tranches}}`
 * - `{{%cash_disbursement}}`
 */
class m231123_030757_create_tbl_fmi_fund_releases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_fund_releases}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->unique()->notNull(),
            'fk_subproject_id' => $this->bigInteger(),
            'fk_tranche_id' => $this->bigInteger(),
            'fk_cash_disbursement_id' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `fk_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_fund_releases-fk_subproject_id}}',
            '{{%tbl_fmi_fund_releases}}',
            'fk_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_fund_releases-fk_subproject_id}}',
            '{{%tbl_fmi_fund_releases}}',
            'fk_subproject_id',
            '{{%tbl_fmi_subprojects}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_tranche_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_fund_releases-fk_tranche_id}}',
            '{{%tbl_fmi_fund_releases}}',
            'fk_tranche_id'
        );

        // add foreign key for table `{{%tbl_fmi_tranches}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_fund_releases-fk_tranche_id}}',
            '{{%tbl_fmi_fund_releases}}',
            'fk_tranche_id',
            '{{%tbl_fmi_tranches}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_cash_disbursement_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_fund_releases-fk_cash_disbursement_id}}',
            '{{%tbl_fmi_fund_releases}}',
            'fk_cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_fund_releases-fk_cash_disbursement_id}}',
            '{{%tbl_fmi_fund_releases}}',
            'fk_cash_disbursement_id',
            '{{%cash_disbursement}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_fund_releases-fk_subproject_id}}',
            '{{%tbl_fmi_fund_releases}}'
        );

        // drops index for column `fk_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_fund_releases-fk_subproject_id}}',
            '{{%tbl_fmi_fund_releases}}'
        );

        // drops foreign key for table `{{%tbl_fmi_tranches}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_fund_releases-fk_tranche_id}}',
            '{{%tbl_fmi_fund_releases}}'
        );

        // drops index for column `fk_tranche_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_fund_releases-fk_tranche_id}}',
            '{{%tbl_fmi_fund_releases}}'
        );

        // drops foreign key for table `{{%cash_disbursement}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_fund_releases-fk_cash_disbursement_id}}',
            '{{%tbl_fmi_fund_releases}}'
        );

        // drops index for column `fk_cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_fund_releases-fk_cash_disbursement_id}}',
            '{{%tbl_fmi_fund_releases}}'
        );

        $this->dropTable('{{%tbl_fmi_fund_releases}}');
    }
}
