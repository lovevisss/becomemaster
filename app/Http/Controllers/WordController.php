<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;

class WordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getTestPage()
    {
        return view('test.index');
    }

    public function test(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return "文件不存在";
        }

        $phpWord = IOFactory::load($file);

        $sections = $phpWord->getSections();
        $rowData = [];

        foreach ($sections as $section) {
            $elements = $section->getElements();
            $sectionData = [];

            foreach ($elements as $element) {
                if ($element instanceof Table) {
                    $sectionData[] = $this->readTable($element);
                }
            }

            $rowData[] = $sectionData;
        }

//        dd($rowData);
        $company = Company::CreateOrUpdate($rowData);
        $user = User::CreateOrUpdate($rowData);
        $user->company()->save($company);
        $project = Project::CreateOrUpdate($rowData);
        Contract::CreateOrUpdate($rowData);

        return view('form.BigPay', compact('rowData'));
    }

    private function readTable($table)
    {
        // 获取表格的所有行
        $rows = $table->getRows();
        $tableData = [];

        foreach ($rows as $row) {
            // 获取行中的所有单元格
            $cells = $row->getCells();
            $rowData = [];

            foreach ($cells as $cell) {
                // 获取单元格中的文本
                $textRuns = $cell->getElements();
                $cellText = '';

                foreach ($textRuns as $textRun) {
                    if ($textRun instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        $cellText .= $textRun->getText();
                    }
                }

                $rowData[] = $cellText;
//                // 打印行数据
//                echo implode("\t", $rowData) . "\n";
            }

            // 将行数据添加到表格数据中
            $tableData[] = $rowData;
        }

        return $tableData;
    }
}
