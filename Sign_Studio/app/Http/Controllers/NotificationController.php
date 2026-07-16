<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommunicationService;
use App\Traits\HttpResponses;

class NotificationController extends Controller
{
    use HttpResponses;
    protected $communicationService;
    
    public function __construct(CommunicationService $communicationService) {
        $this->communicationService = $communicationService;
    }
    
    public function index() { return $this->success(null, 'Notifications fetched successfully.'); }
    public function store(Request $request) { return $this->success(null, 'Notification sent.'); }
}
