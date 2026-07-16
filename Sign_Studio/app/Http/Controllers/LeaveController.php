<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HrService;
use App\Traits\HttpResponses;

class LeaveController extends Controller
{
    use HttpResponses;
    protected $hrService;
    
    public function __construct(HrService $hrService) {
        $this->hrService = $hrService;
    }
    
    public function index() { return $this->success(null, 'Leaves fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Leave applied.'); }
}
