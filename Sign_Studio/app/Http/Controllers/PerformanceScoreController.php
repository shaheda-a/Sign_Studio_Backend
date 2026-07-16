<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PerformanceScoreService;
use App\Traits\HttpResponses;

class PerformanceScoreController extends Controller
{
    use HttpResponses;
    protected $performanceScoreService;
    
    public function __construct(PerformanceScoreService $performanceScoreService) {
        $this->performanceScoreService = $performanceScoreService;
    }
    
    public function index() { return $this->success(null, 'Performance scores fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Performance score created.'); }
}
