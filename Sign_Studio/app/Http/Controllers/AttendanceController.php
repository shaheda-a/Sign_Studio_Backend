<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HrService;
use App\Traits\HttpResponses;

class AttendanceController extends Controller
{
    use HttpResponses;
    protected $hrService;
    
    public function __construct(HrService $hrService) {
        $this->hrService = $hrService;
    }
    
    public function index() { return $this->success(null, 'Attendances fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Attendance created.'); }
}
