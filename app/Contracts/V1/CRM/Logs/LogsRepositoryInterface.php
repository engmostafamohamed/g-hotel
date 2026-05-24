<?php
namespace App\Contracts\V1\CRM\Logs;
use Illuminate\Http\Request;

interface LogsRepositoryInterface
{

    public function showLogsRepository(Request $request);

}
