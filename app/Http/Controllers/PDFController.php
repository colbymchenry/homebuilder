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
        $choices = [];
        $sub_totals = [];

        foreach(\request()->toArray() as $key => $value) {
            if($key !== '_token' && $value != null) {
                if(strpos($key, 'design_option_') !== false) {
                    $id = explode('_', $key)[2];
                    if(DesignOption::where('id', $id)->exists()) {

                        $choices[$id] = $value;
                        $design_option = DesignOption::where('id', $id)->first();
                        if(array_key_exists($design_option->category, $sub_totals)) {
                            $sub_totals[$design_option->category] += PriceSheet::where('id', $value)->first()->price;
                            \Log::info($design_option->name . '+=' . $design_option->category);
                        } else {
                            $sub_totals[$design_option->category] = PriceSheet::where('id', $value)->first()->price;
                            \Log::info($design_option->name . '=' . $design_option->category);
                        }
                    }
                }
            }
        }

        return view('pdfs.plan_build_summary')->with('choices', $choices)->with('sub_totals', $sub_totals)
        ->with('house_plan', \request('house_plan'))->with('project', \request('project'))->with('lot', \request('lot'));
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
