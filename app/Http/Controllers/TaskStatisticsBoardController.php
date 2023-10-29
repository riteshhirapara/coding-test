<?php
namespace App\Http\Controllers;

class TaskStatisticsBoardController extends Controller
{

    public function taskStatistics()
    {
        return view('tasks.statistics.index');
    }
}
