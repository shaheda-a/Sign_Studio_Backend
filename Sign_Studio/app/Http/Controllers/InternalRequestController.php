<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DailyControlService;
use App\Traits\HttpResponses;

class InternalRequestController extends Controller
{
    use HttpResponses;
    protected $dailyControlService;
    
    public function __construct(DailyControlService $dailyControlService) {
        $this->dailyControlService = $dailyControlService;
    }
    
    public function index() { return $this->success(null, 'Internal requests fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Internal request created.'); }
}
