<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(){
        if(request()->file !== null) {
            // Upload path
            $destinationPath = 'files/';
    
            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
    
            // Get file extension
            $extension = request()->file->getClientOriginalExtension();
    
            // Valid extensions
            $validextensions = array('jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'zip', 'jpeg');
    
            // Check extension
            if(in_array(strtolower($extension), $validextensions)) {
                $file_name = request()->table . '_' . request()->relational_id . '_' . request()->file->getClientOriginalName();
                // Uploading file to given path
                request()->file->move($destinationPath, $file_name); 
                
                $file = new File();
                $file->name = request()->file->getClientOriginalName();
                $file->relational_table = request()->table;
                $file->relational_id = request()->relational_id;
                $file->save();
                
                return response()->json(['uploaded' => $destinationPath . '/' . $file_name]);
            }
        }

        return response()->json(['Failed to upload file.']);
    }

}
