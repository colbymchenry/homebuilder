<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\QuickResponse;
use App\TaskTemplate;
use App\TemplateTask;
use PayPal\Api\Template;

class ContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function contactsIndex() {
        return view ('contacts');
    }

}
