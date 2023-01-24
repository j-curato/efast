<?php

use yii\db\Migration;

/**
 * Class m230112_082416_add_fk_constraints_in_supplemental_ppmp_table
 */
class m230112_082416_add_fk_constraints_in_supplemental_ppmp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        // add foreign key for table `{{%office}}`
        $this->createIndex(
            '{{%idx-fk_office_id}}',
            '{{%supplemental_ppmp}}',
            'fk_office_id'
        );
        // add foreign key for table `{{%fk_division_id}}`
        $this->createIndex(
            '{{%idx-fk_division_id}}',
            '{{%supplemental_ppmp}}',
            'fk_division_id'
        );
        // add foreign key for table `{{%fk_division_program_unit_id}}`
        $this->createIndex(
            '{{%idx-fk_division_program_unit_id}}',
            '{{%supplemental_ppmp}}',
            'fk_division_program_unit_id'
        );
        // add foreign key for table `{{%fk_prepared_by}}`
        $this->createIndex(
            '{{%idx-fk_prepared_by}}',
            '{{%supplemental_ppmp}}',
            'fk_prepared_by'
        );
        // add foreign key for table `{{%fk_reviewed_by}}`
        $this->createIndex(
            '{{%idx-fk_reviewed_by}}',
            '{{%supplemental_ppmp}}',
            'fk_reviewed_by'
        );
        // add foreign key for table `{{%fk_approved_by}}`
        $this->createIndex(
            '{{%idx-fk_approved_by}}',
            '{{%supplemental_ppmp}}',
            'fk_approved_by'
        );
        // add foreign key for table `{{%fk_certified_funds_available_by}}`
        $this->createIndex(
            '{{%idx-fk_certified_funds_available_by}}',
            '{{%supplemental_ppmp}}',
            'fk_certified_funds_available_by'
        );
        // 
        // 
        // 
        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-fk_office_id}}',
            '{{%supplemental_ppmp}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
        // add foreign key for table `{{%divisions}}`
        $this->addForeignKey(
            '{{%fk-fk_division_id}}',
            '{{%supplemental_ppmp}}',
            'fk_division_id',
            '{{%divisions}}',
            'id',
            'RESTRICT'
        );
        // add foreign key for table `{{%division_program_unit}}`
        $this->addForeignKey(
            '{{%fk-fk_division_program_unit_id}}',
            '{{%supplemental_ppmp}}',
            'fk_division_program_unit_id',
            '{{%division_program_unit}}',
            'id',
            'RESTRICT'
        );
        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-fk_prepared_by}}',
            '{{%supplemental_ppmp}}',
            'fk_prepared_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );
        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-fk_reviewed_by}}',
            '{{%supplemental_ppmp}}',
            'fk_reviewed_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );
        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-fk_approved_by}}',
            '{{%supplemental_ppmp}}',
            'fk_approved_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );
        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-fk_certified_funds_available_by}}',
            '{{%supplemental_ppmp}}',
            'fk_certified_funds_available_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%fk_office_id}}`

        $this->dropForeignKey(
            '{{%fk-fk_office_id}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropForeignKey(
            '{{%fk-fk_division_id}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropForeignKey(
            '{{%fk-fk_division_program_unit_id}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropForeignKey(
            '{{%fk-fk_prepared_by}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropForeignKey(
            '{{%fk-fk_reviewed_by}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropForeignKey(
            '{{%fk-fk_approved_by}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropForeignKey(
            '{{%fk-fk_certified_funds_available_by}}',
            '{{%supplemental_ppmp}}'
        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-fk_office_id}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropIndex(
            '{{%idx-fk_division_id}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropIndex(
            '{{%idx-fk_division_program_unit_id}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropIndex(
            '{{%idx-fk_prepared_by}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropIndex(
            '{{%idx-fk_reviewed_by}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropIndex(
            '{{%idx-fk_approved_by}}',
            '{{%supplemental_ppmp}}'
        );
        $this->dropIndex(
            '{{%idx-fk_certified_funds_available_by}}',
            '{{%supplemental_ppmp}}'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230112_082416_add_fk_constraints_in_supplemental_ppmp_table cannot be reverted.\n";

        return false;
    }
    */
}
