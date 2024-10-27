<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;

class JobController extends Controller
{
    public function create(CreateJobRequest $request)
    {
        dd($request->all());
    }
}
