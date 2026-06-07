<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Auth;
use App\Core\Mailer;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Upload;
use App\Controllers\Panel\TicketController as PanelTicket;

class TicketController extends AdminController
{
    public function index(Request $request): never
    {
        $tickets = (new Ticket())->withUser();
        $this->view('admin/tickets', ['pageTitle' => 'Destek Talepleri', 'tickets' => $tickets]);
    }

    public function show(Request $request, array $params): never
    {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find((int) $params['id']);
        if (!$ticket) {
            $this->abort(404);
        }
        $customer = (new User())->find((int) $ticket['user_id']);
        $messages = $ticketModel->messages((int) $ticket['id'], true); // dahili notlar dahil
        $this->view('admin/ticket-show', [
            'pageTitle' => 'Talep #' . $ticket['id'],
            'ticket'    => $ticket,
            'customer'  => $customer,
            'messages'  => $messages,
        ]);
    }

    public function reply(Request $request, array $params): never
    {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find((int) $params['id']);
        if (!$ticket) {
            $this->abort(404);
        }
        $message = $request->string('message');
        if (mb_strlen($message) < 1) {
            $this->withError('Mesaj bos olamaz.');
            $this->redirect('/admin/destek/' . $ticket['id']);
        }
        $internal = $request->bool('internal');

        $attachment = null;
        try {
            $attachment = Upload::handle($request->file('attachment'), 'tickets');
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage());
        }

        $ticketModel->addMessage((int) $ticket['id'], 'admin', Auth::id(Auth::GUARD_ADMIN), $message, $attachment, $internal);
        $ticketModel->update((int) $ticket['id'], ['status' => $internal ? $ticket['status'] : 'answered']);

        // Dahili not degilse musteriye bildir
        if (!$internal) {
            $customer = (new User())->find((int) $ticket['user_id']);
            if ($customer && !empty($customer['notify_email'])) {
                Mailer::send($customer['email'], 'Destek Talebiniz Yanitlandi — #' . $ticket['id'], 'ticket-update', [
                    'heading' => 'Talebiniz Yanitlandi',
                    'body'    => 'Destek ekibimiz "' . $ticket['subject'] . '" konulu talebinize yanit verdi.',
                    'link'    => base_url('panel/destek/' . $ticket['id']),
                ], $customer['name']);
            }
        }

        $this->withSuccess($internal ? 'Dahili not eklendi.' : 'Yanit gonderildi.');
        $this->redirect('/admin/destek/' . $ticket['id']);
    }

    public function updateStatus(Request $request, array $params): never
    {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find((int) $params['id']);
        if (!$ticket) {
            $this->abort(404);
        }
        $status = $request->string('status');
        if (in_array($status, ['open', 'answered', 'closed'], true)) {
            $ticketModel->update((int) $ticket['id'], ['status' => $status]);
            $this->withSuccess('Durum guncellendi.');
        }
        $this->redirect('/admin/destek/' . $ticket['id']);
    }

    public function attachment(Request $request, array $params): never
    {
        $msg = $this->db()->fetch('SELECT * FROM ticket_messages WHERE id = :id', ['id' => (int) $params['id']]);
        if (!$msg || empty($msg['attachment_path'])) {
            $this->abort(404);
        }
        PanelTicket::serveFile(PUNCH_ROOT . '/storage/' . $msg['attachment_path']);
    }
}
