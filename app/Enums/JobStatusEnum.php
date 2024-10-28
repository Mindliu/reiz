<?php

namespace App\Enums;

enum JobStatusEnum: string
{
    case QUEUED = 'queued';
    case STARTED = 'started';
    case DONE = 'done';
    case FAILED = 'failed';
}
