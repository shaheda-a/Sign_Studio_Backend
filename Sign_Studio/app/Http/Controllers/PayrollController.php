<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayrollService;
use App\Traits\HttpResponses;

class PayrollController extends Controller
{
    use HttpResponses;
    protected $payrollService;
    
    public function __construct(PayrollService $payrollService) {
        $this->payrollService = $payrollService;
    }
    
    public function index() { return $this->success(null, 'Payrolls fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Payroll generated.'); }
}
