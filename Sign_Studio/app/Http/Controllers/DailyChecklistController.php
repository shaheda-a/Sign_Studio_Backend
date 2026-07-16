<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DailyControlService;
use App\Traits\HttpResponses;

class DailyChecklistController extends Controller
{
    use HttpResponses;
    protected $dailyControlService;
    
    public function __construct(DailyControlService $dailyControlService) {
        $this->dailyControlService = $dailyControlService;
    }
    
    public function index() { return $this->success(null, 'Daily checklists fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Daily checklist submitted.'); }
}
