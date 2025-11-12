<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Dashboard\SummaryAction;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function summary(SummaryAction $action)
    {
        return $action();
    }
}