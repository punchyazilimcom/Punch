<?php

namespace App\Controllers\Admin;

use App\Core\Request;

class ContactController extends AdminController
{
    public function index(Request $request): never
    {
        // Goruntulenince okundu say
        $this->db()->query('UPDATE contact_messages SET is_read = 1 WHERE is_read = 0');
        $messages = $this->db()->fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 200');
        $this->view('admin/messages', ['pageTitle' => 'Iletisim Mesajlari', 'messages' => $messages]);
    }
}
