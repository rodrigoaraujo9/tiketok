<?php

use App\Models\Notification;

function generateNotificationMessage($notification) {
    $data = json_decode($notification->data, true);
    switch ($notification->type) {
        case 'invite':
            return [
                'message' => "Foi convidado para o evento {$data['event_name']}.",
                'url' => route('invites.show', ['event_id' => $data['event_id']]),
            ];
        case 'report_update':
            return [
                'message' => "O report que fez no evento {$data['event_name']} foi atualizado.",
                'url' => route('reports.show', ['event_id' => $data['event_id']]),
            ];
        default:
            return ['message' => 'Notificação desconhecida.', 'url' => '#'];
    }
}
