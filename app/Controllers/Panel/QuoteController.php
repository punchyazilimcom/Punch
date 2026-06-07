<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Quote;

class QuoteController extends Controller
{
    public function index(Request $request): never
    {
        $quotes = (new Quote())->forUser(Auth::id(Auth::GUARD_USER));
        $this->render('panel/quotes', [
            'pageTitle' => 'Tekliflerim',
            'quotes'    => $quotes,
        ], 'layouts/panel');
    }

    public function updateStatus(Request $request, array $params): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $quoteModel = new Quote();
        $quote = $quoteModel->find((int) $params['id']);
        if (!$quote || (int) ($quote['user_id'] ?? 0) !== $uid) {
            $this->abort(404);
        }
        // Yalniz teklif verilmis durumdaki teklifler kabul/red edilebilir
        $action = $request->string('action');
        if ($quote['status'] === 'quoted' && in_array($action, ['accept', 'reject'], true)) {
            $quoteModel->update((int) $quote['id'], ['status' => $action === 'accept' ? 'accepted' : 'rejected']);
            $this->withSuccess($action === 'accept' ? 'Teklifi kabul ettiniz. Ekibimiz sizinle iletisime gececek.' : 'Teklifi reddettiniz.');
        }
        $this->redirect('/panel/teklifler');
    }
}
