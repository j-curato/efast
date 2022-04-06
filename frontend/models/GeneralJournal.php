<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "general_journal".
 *
 * @property int $id
 * @property int $book_id
 * @property string|null $reporting_period
 */
class GeneralJournal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_journal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id'], 'required'],
            [['book_id'], 'integer'],
            [['reporting_period'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'reporting_period' => 'Reporting Period',
        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function actionExport()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $book_id  = $_POST['book_id'];
            $book_name = Books::findOne($book_id)->name;
            $query  = $this->query($to_reporting_period, $book_id, $entry_type);
            $month = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('F Y');


            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', "DEPARTMENT OF TRADE AND INDUSTRY ");
            $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A2:D2');
            $sheet->setCellValue('A2', "CARAGA REGIONAL OFFICE");
            $sheet->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A3:D3');
            $sheet->setCellValue('A3', "Trial Balance $book_name");
            $sheet->getStyle('A3:D3')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A4:D4');
            $sheet->setCellValue('A4', "As of $month");
            $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A5', "Acount Name");
            $sheet->setCellValue('B5', "Object COde");
            $sheet->setCellValue('C5', "Debit");
            $sheet->setCellValue('D5', "Credit");



            $row = 6;
            $total_debit = 0;
            $total_credit = 0;
            foreach ($query  as  $val) {

                $total = $val['total_debit_credit'];
                $normal_balance = $val['normal_balance'];
                $debit = '';
                $credit = '';
                if (strtolower($normal_balance) == null) {
                    $debit = "No Normal Balance";
                    $credit = "No Normal Balance";
                } else if (strtolower($normal_balance) == "debit") {
                    if ($total < 0) {

                        $credit = number_format($total * -1, 2);
                        $total_credit += $total * -1;
                    } else {
                        $debit = number_format($total, 2);
                        $total_debit += $total;
                    }
                } else if (strtolower($normal_balance) == "credit") {
                    if ($total < 0) {

                        $debit = number_format($total * -1, 2);
                        $total_debit += $total * -1;
                    } else {
                        $credit = number_format($total, 2);
                        $total_credit += $total;
                    }
                }

                $sheet->setCellValueByColumnAndRow(1, $row,  $val['account_title']);
                $sheet->setCellValueByColumnAndRow(2, $row,  $val['object_code']);
                $sheet->setCellValueByColumnAndRow(3, $row, $debit);
                $sheet->setCellValueByColumnAndRow(4, $row, $credit);
                $row++;
            }
            $sheet->mergeCellsByColumnAndRow(1, $row, 2, $row);
            $sheet->setCellValueByColumnAndRow(1, $row, 'Total');
            $sheet->setCellValueByColumnAndRow(3, $row, number_format($total_debit, 2));
            $sheet->setCellValueByColumnAndRow(4, $row, number_format($total_credit, 2));
            foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            date_default_timezone_set('Asia/Manila');
            $id = 'trial_balance_' . $book_name . '_' . $to_reporting_period . '_' . uniqid();
            $file_name = "$id.xlsx";
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            $path = Yii::getAlias('@webroot') . '/transaction';

            $file = $path . "/$id.xlsx";
            $file2 = "transaction/$id.xlsx";
            $writer->save($file);
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");

            return json_encode($file2);


            exit();
        }
    }
}
