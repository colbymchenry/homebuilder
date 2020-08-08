<?php

namespace App\Http\Controllers;

use App\File;
use App\Note;
use App\QuickResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NoteController extends Controller
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

    public function create(Request $request){
        $note = new Note();
        $note->text = $request['text'];
        $note->relational_table = $request['relational_table'];
        $note->relational_id = $request['relational_id'];
        $note->save();
        return QuickResponse::success('Note created.', ['timestamp' => $note->getEST()]);
    }

    public function delete(Request $request) {
        if(Note::where('id', $request['id'])->exists()) {
            $note = Note::where("id", $request['id'])->first();
            $note->delete();
            return QuickResponse::success('Note deleted');
        }

        return QuickResponse::warning('Note not found.');
    }

}
