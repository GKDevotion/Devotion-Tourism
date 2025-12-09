<?php 

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileController extends Controller
{
    /**
     * 
     */
    public function index() { 
        return view('file');
    }

    /**
     * 
     */
    public function store(Request $request) {

        $file = $request->file;

        $request->validate([
            'file' => 'required',
        ]);

        // use of pdf parser to read content from pdf 
        // $fileName = $file->getClientOriginalName();

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($file->path());
        $content = $pdf->getText();

        dd( $content );
        return redirect()->back()->with('success', 'File  submitted');
    }

    /**
     * 
     */
    public function readGoogleExcelSheet(){
        $inputFileName = 'https://docs.google.com/spreadsheets/d/1A8nRGWQYJTEQqOxSzyjCclv8Yp_lVtLJeRFtlBkS2SQ/edit?gid=893430658#gid=893430658';

        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        dd($sheetData);

        // foreach ($sheetData as $rows=>$k) {
        //         $num = $rows;
        //     foreach ($k as $key=>$value) {
        //         $excel = new Excel;
        //         $excel->cell_number = $num;
        //         $excel->cell_letter = $key;
        //         $excel->cell_value = $value;   
        //         $excel->save(); 
        //     }
        // }

        // return view('excel', compact('sheetData'));
    }
}