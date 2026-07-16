<?php

namespace App\Services;

use App\Models\ServiceTicket;
use Illuminate\Support\Facades\DB;

class ServiceTicketService
{
    public function createTicket(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['ticket_number'] = 'TKT-' . strtoupper(uniqid());
            $ticket = ServiceTicket::create($data);
            return $ticket;
        });
    }

    public function updateTicketStatus(ServiceTicket $ticket, string $status)
    {
        $ticket->update(['status' => $status]);
        if ($status === 'closed') {
            $ticket->update(['closed_at' => now()]);
        }
        return $ticket;
    }
}
