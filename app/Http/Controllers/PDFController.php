<?php

namespace App\Http\Controllers;

use App\DesignOption;
use App\PriceSheet;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

// https://github.com/dompdf/dompdf
class PDFController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generatePDF_PlanBuildout() {
        $sub_total = 0;
        $choices = [];
        foreach(\request()->toArray() as $key => $value) {
            if($key !== '_token') {
                if(strpos($key, 'design_option_') !== false) {
                    $id = explode('_', $key)[2];
                    if(DesignOption::where('id', $id)->exists()) {
                        $choices[$id] = $value;
                        $sub_total += PriceSheet::where('id', $value)->first()->price;
                    }
                }
            }
        }

        return view('pdfs.plan_build_summary')->with('choices', $choices)->with('sub_total', $this->getFormattedPrice($sub_total));
    }

    public function saveToFile($path, $output) {
        //save the pdf file on the server
        file_put_contents($path, $output); 
        //print the pdf file to the screen for saving
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="file.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($path));
        header('Accept-Ranges: bytes');
        readfile($path);
    }

    public function getFormattedPrice($arg) {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $fmt->setTextAttribute(\NumberFormatter::CURRENCY_CODE, 'USD');
        $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        return  $fmt->formatCurrency($arg, 'USD');
    }

}
