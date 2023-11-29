<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_subprojects}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%provinces}}`
 * - `{{%municipalities}}`
 * - `{{%barangays}}`
 * - `{{%tbl_fmi_batches}}`
 */
class m231122_013559_create_tbl_fmi_subprojects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_subprojects}}', [
            'id' => $this->primaryKey(),
            'serial_number'=>$this->string()->unique(),
            'fk_office_id' => $this->integer(),
            'fk_province_id' => $this->integer(),
            'fk_municipality_id' => $this->integer(),
            'fk_barangay_id' => $this->integer(),
            'purok' => $this->text(),
            'fk_fmi_batch_id' => $this->bigInteger(),
            'project_duration' => $this->integer(),
            'project_road_length' => $this->integer(),
            'project_start_date' => $this->date(),
            'grant_amount' => $this->decimal(15, 2),
            'equity_amount' => $this->decimal(15, 2),
            'bank_account_name' => $this->string(),
            'bank_account_number' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('tbl_fmi_subprojects', 'id', $this->bigInteger());
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_subprojects-fk_office_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_office_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_province_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_subprojects-fk_province_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_province_id'
        );

        // add foreign key for table `{{%provinces}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_province_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_province_id',
            '{{%provinces}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_municipality_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_subprojects-fk_municipality_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_municipality_id'
        );

        // add foreign key for table `{{%municipalities}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_municipality_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_municipality_id',
            '{{%municipalities}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_barangay_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_subprojects-fk_barangay_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_barangay_id'
        );

        // add foreign key for table `{{%barangays}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_barangay_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_barangay_id',
            '{{%barangays}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_fmi_batch_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_subprojects-fk_fmi_batch_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_fmi_batch_id'
        );

        // add foreign key for table `{{%tbl_fmi_batches}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_fmi_batch_id}}',
            '{{%tbl_fmi_subprojects}}',
            'fk_fmi_batch_id',
            '{{%tbl_fmi_batches}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%provinces}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_province_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops index for column `fk_province_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_subprojects-fk_province_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops foreign key for table `{{%municipalities}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_municipality_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops index for column `fk_municipality_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_subprojects-fk_municipality_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops foreign key for table `{{%barangays}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_barangay_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops index for column `fk_barangay_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_subprojects-fk_barangay_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops foreign key for table `{{%tbl_fmi_batches}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_subprojects-fk_fmi_batch_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        // drops index for column `fk_fmi_batch_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_subprojects-fk_fmi_batch_id}}',
            '{{%tbl_fmi_subprojects}}'
        );

        $this->dropTable('{{%tbl_fmi_subprojects}}');
    }
}
