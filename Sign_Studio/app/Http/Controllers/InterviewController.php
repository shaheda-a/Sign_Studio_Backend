<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecruitmentService;
use App\Traits\HttpResponses;

class InterviewController extends Controller
{
    use HttpResponses;
    protected $recruitmentService;
    
    public function __construct(RecruitmentService $recruitmentService) {
        $this->recruitmentService = $recruitmentService;
    }
    
    public function index() { return $this->success(null, 'Interviews fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Interview scheduled.'); }
}
