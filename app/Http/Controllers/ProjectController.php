<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;

class ProjectController extends Controller
{
    // need to login before
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'days' => $request->days,
            'company_id' => $request->company_id,
//            'user_id' => auth()->user()->id,
        ]);

        // with message success
        session()->flash('message', 'Project created successfully');
        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project = Project::where('id', $project->id)->with('tasks')->first();
        $comments = $project->comments;
        return view('projects.show', compact('project', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $projectUpdate = Project::where('id', $project->id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($projectUpdate) {
            session()->flash('message', 'Project updated successfully');
        }

        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        session()->flash('message', 'Project deleted successfully');
    }

    public function getBigPayForm(Project $project)
    {
        $file = public_path('/excel/bigPay.docx');

        $phpWord = IOFactory::load($file);
        $forms = [
            "收款单位" => $project->company->name,
            "联系人" => $project->company->user->name,
            "联系电话" => $project->company->user->phone,

        ];

//        dd($forms);

        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if ($element instanceof Table) {
                    $this->writeTable($element, $forms);
                }
            }
        }
        $out_put_path = public_path('/excel/helloWorld.docx');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($out_put_path);
        return response()->download($out_put_path);
    }


    private function writeTable($table, $forms)
    {
        $rows = $table->getRows();
        foreach ($rows as $row) {
            $cells = $row->getCells();
//            echo count($cells);
            foreach ($cells as $keyCell => $cell) {
                $textRuns = $cell->getElements();
                foreach ($textRuns as $textRun) {
                    if ($textRun instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        $text = $textRun->getText();
                        foreach ($forms as $key => $value) {
                            if (str_contains($text, $key)) {
//                                dd($keyCell);
                                $targetCell = $cells[$keyCell+1];
                                $netTextRuns = $targetCell->getElements();
                                foreach ($netTextRuns as $netTextRun) {
                                    if ($netTextRun instanceof \PhpOffice\PhpWord\Element\TextRun ) {
                                        $netTextRun->addText($value);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


}
