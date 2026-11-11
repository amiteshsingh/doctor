<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class GenerateQuizTemplate extends Command
{
    protected $signature   = 'quiz:generate-template';
    protected $description = 'Generate sample quiz Excel template in public/sample/';

    public function handle(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Quiz Questions');

        // ── Headers ──────────────────────────────────────────────────────────
        $headers = ['Question', 'Option A', 'Option B', 'Option C', 'Option D',
                    'Correct Answer (A/B/C/D)', 'Category', 'Time Limit (sec)', 'Explanation (optional)'];

        $cols = ['A','B','C','D','E','F','G','H','I'];
        foreach ($headers as $i => $value) {
            $sheet->setCellValue($cols[$i] . '1', $value);
        }

        $sheet->getStyle('A1:I1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A237E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color'       => ['rgb' => '3949AB']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // ── Sample Rows ───────────────────────────────────────────────────────
        $rows = [
            ['Human body mein kitni haddiyaan hoti hain?',
             '206', '208', '210', '212', 'A', 'Health', 30,
             'Ek adult human body mein total 206 bones hoti hain.'],

            ['Paani ka chemical formula kya hai?',
             'H2O', 'CO2', 'O2', 'H2SO4', 'A', 'Science', 20,
             'Paani 2 hydrogen aur 1 oxygen atom se milkar bana hai.'],

            ['India ka sabse bada state (area ke hisaab se) kaun sa hai?',
             'Maharashtra', 'Madhya Pradesh', 'Rajasthan', 'Uttar Pradesh', 'C', 'Geography', 30,
             'Rajasthan 342,239 sq km ke sath India ka sabse bada state hai.'],

            ['Dil (Heart) ek minute mein kitni baar dhadakta hai (average)?',
             '40-50', '60-100', '110-130', '150-180', 'B', 'Health', 25,
             'Ek healthy adult ka heart rate 60-100 beats per minute hota hai.'],

            ['Olympic Games kitne saal mein ek baar hote hain?',
             '2 saal', '3 saal', '4 saal', '5 saal', 'C', 'Sports', 20,
             'Olympic Games har 4 saal mein ek baar aayojit hote hain.'],

            ['CPU ka full form kya hai?',
             'Central Processing Unit', 'Computer Personal Unit',
             'Central Program Utility', 'Core Processing Unit', 'A', 'Technology', 25,
             'CPU = Central Processing Unit — yeh computer ka "brain" hota hai.'],

            ['1 + 1 ka answer kya hai binary mein?',
             '2', '10', '11', '1', 'B', 'Math', 30,
             'Binary mein 1+1 = 10 hota hai (carry 1, sum 0).'],

            ['Mahatma Gandhi ka janm kab hua tha?',
             '2 October 1869', '15 August 1872', '26 January 1869', '2 October 1879',
             'A', 'History', 30,
             'Mahatma Gandhi ka janm 2 October 1869 ko Porbandar, Gujarat mein hua tha.'],

            ['Sabse gehri ocean trench kaun si hai?',
             'Java Trench', 'Puerto Rico Trench', 'Mariana Trench', 'Tonga Trench',
             'C', 'General', 30,
             'Mariana Trench Pacific Ocean mein hai aur ~11,000 meter gehri hai.'],

            ['Python programming language kab banaayi gayi thi?',
             '1985', '1991', '1998', '2003', 'B', 'Technology', 25,
             'Guido van Rossum ne Python 1991 mein release ki thi.'],
        ];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2;
            foreach ($row as $col => $value) {
                $sheet->setCellValue($cols[$col] . $rowNum, $value);
            }
            $bg = ($i % 2 === 0) ? 'F5F5FF' : 'FFFFFF';
            $sheet->getStyle("A{$rowNum}:I{$rowNum}")->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bg);
            $sheet->getStyle("A{$rowNum}:I{$rowNum}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN)
                ->getColor()->setRGB('CCCCCC');
            $sheet->getStyle("A{$rowNum}")->getAlignment()->setWrapText(true);
            $sheet->getStyle("I{$rowNum}")->getAlignment()->setWrapText(true);
        }

        // ── Dropdowns ─────────────────────────────────────────────────────────
        $av = $sheet->getDataValidation('F2:F1000');
        $av->setType(DataValidation::TYPE_LIST)->setErrorStyle(DataValidation::STYLE_STOP)
           ->setAllowBlank(false)->setShowDropDown(false)
           ->setShowErrorMessage(true)->setErrorTitle('Galat Value')
           ->setError('Sirf A, B, C ya D enter karo.')->setFormula1('"A,B,C,D"');

        $cv = $sheet->getDataValidation('G2:G1000');
        $cv->setType(DataValidation::TYPE_LIST)->setErrorStyle(DataValidation::STYLE_INFORMATION)
           ->setAllowBlank(true)->setShowDropDown(false)
           ->setFormula1('"General,Health,Science,History,Geography,Sports,Technology,Math"');

        // ── Column Widths ─────────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(55);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(22);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(16);
        $sheet->getColumnDimension('H')->setWidth(16);
        $sheet->getColumnDimension('I')->setWidth(45);
        $sheet->freezePane('A2');

        // ── Save ──────────────────────────────────────────────────────────────
        $dir = public_path('sample');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $path = $dir . '/quiz_import_sample.xlsx';
        IOFactory::createWriter($spreadsheet, 'Xlsx')->save($path);

        $this->info("✅ File saved: public/sample/quiz_import_sample.xlsx");
    }
}
