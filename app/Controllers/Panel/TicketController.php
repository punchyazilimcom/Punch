<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Validator;
use App\Core\Session;
use App\Core\Mailer;
use App\Models\Ticket;
use App\Services\Upload;

class TicketController extends Controller
{
    public function index(Request $request): never
    {
        $tickets = (new Ticket())->forUser(Auth::id(Auth::GUARD_USER));
        $this->render('panel/tickets', [
            'pageTitle' => 'Destek Talepleri',
            'tickets'   => $tickets,
        ], 'layouts/panel');
    }

    public function create(Request $request): never
    {
        $this->render('panel/ticket-create', ['pageTitle' => 'Yeni Destek Talebi'], 'layouts/panel');
    }

    public function store(Request $request): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $data = $request->only(['subject', 'priority', 'message']);
        $v = new Validator($data, ['subject' => 'Konu', 'message' => 'Mesaj']);
        if (!$v->validate([
            'subject' => 'required|min:3|max:160',
            'priority' => 'in:low,normal,high',
            'message' => 'required|min:5|max:5000',
        ])) {
            Session::flashInput($data);
            Session::set('_errors', $v->firstErrors());
            $this->back('/panel/destek/yeni');
        }

        $ticketModel = new Ticket();
        $ticketId = $ticketModel->create([
            'user_id'  => $uid,
            'subject'  => $data['subject'],
            'priority' => in_array($data['priority'] ?? 'normal', ['low', 'normal', 'high'], true) ? $data['priority'] : 'normal',
            'status'   => 'open',
        ]);

        $attachment = null;
        try {
            $attachment = Upload::handle($request->file('attachment'), 'tickets');
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage());
        }

        $ticketModel->addMessage($ticketId, 'user', $uid, $data['message'], $attachment);

        Mailer::toAdmin('Yeni Destek Talebi: ' . $data['subject'], 'ticket-update', [
            'heading' => 'Yeni Destek Talebi',
            'body'    => 'Bir musteri yeni destek talebi olusturdu: ' . $data['subject'],
            'link'    => base_url('admin/destek/' . $ticketId),
        ]);

        Session::clearOld();
        $this->withSuccess('Destek talebiniz olusturuldu.');
        $this->redirect('/panel/destek/' . $ticketId);
    }

    public function show(Request $request, array $params): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find((int) $params['id']);
        if (!$ticket || (int) $ticket['user_id'] !== $uid) {
            $this->abort(404);
        }
        $messages = $ticketModel->messages((int) $ticket['id'], false); // dahili not haric
        $this->render('panel/ticket-show', [
            'pageTitle' => 'Talep #' . $ticket['id'],
            'ticket'    => $ticket,
            'messages'  => $messages,
        ], 'layouts/panel');
    }

    public function reply(Request $request, array $params): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find((int) $params['id']);
        if (!$ticket || (int) $ticket['user_id'] !== $uid) {
            $this->abort(404);
        }
        if ($ticket['status'] === 'closed') {
            $this->withError('Bu talep kapatilmis. Yeni bir talep olusturabilirsiniz.');
            $this->redirect('/panel/destek/' . $ticket['id']);
        }

        $message = $request->string('message');
        if (mb_strlen($message) < 2) {
            $this->withError('Lutfen bir mesaj yazin.');
            $this->redirect('/panel/destek/' . $ticket['id']);
        }

        $attachment = null;
        try {
            $attachment = Upload::handle($request->file('attachment'), 'tickets');
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage());
        }

        $ticketModel->addMessage((int) $ticket['id'], 'user', $uid, $message, $attachment);
        $ticketModel->update((int) $ticket['id'], ['status' => 'open']);

        $this->withSuccess('Yanitiniz gonderildi.');
        $this->redirect('/panel/destek/' . $ticket['id']);
    }

    public function attachment(Request $request, array $params): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $db = \App\Core\App::get()->db();
        $msg = $db->fetch(
            'SELECT tm.*, t.user_id FROM ticket_messages tm
             JOIN tickets t ON t.id = tm.ticket_id WHERE tm.id = :id',
            ['id' => (int) $params['id']]
        );
        if (!$msg || (int) $msg['user_id'] !== $uid || empty($msg['attachment_path'])) {
            $this->abort(404);
        }
        self::serveFile(PUNCH_ROOT . '/storage/' . $msg['attachment_path']);
    }

    public static function serveFile(string $path): never
    {
        if (!is_file($path)) {
            http_response_code(404);
            exit('Dosya bulunamadi');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        header('Content-Type: ' . $finfo->file($path));
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
