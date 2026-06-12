<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Validator;
use App\Core\Session;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request): never
    {
        $this->render('panel/profile', [
            'pageTitle' => 'Profil & Guvenlik',
            'me'        => Auth::user(Auth::GUARD_USER),
        ], 'layouts/panel');
    }

    public function update(Request $request): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $data = $request->only(['name', 'phone', 'company', 'tax_no', 'tc_no', 'address', 'city']);
        $v = new Validator($data, ['name' => 'Ad Soyad']);
        if (!$v->validate(['name' => 'required|min:2|max:120', 'phone' => 'phone'])) {
            Session::set('_errors', $v->firstErrors());
            $this->back('/panel/profil');
        }

        (new User())->update($uid, [
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?: null,
            'company' => $data['company'] ?: null,
            'tax_no'  => $data['tax_no'] ?: null,
            'tc_no'   => $data['tc_no'] ?: null,
            'address' => $data['address'] ?: null,
            'city'    => $data['city'] ?: null,
            'notify_email' => $request->bool('notify_email') ? 1 : 0,
        ]);

        $this->withSuccess('Profil bilgileriniz guncellendi.');
        $this->redirect('/panel/profil');
    }

    public function password(Request $request): never
    {
        $me = Auth::user(Auth::GUARD_USER);
        $current = (string) $request->input('current_password', '');
        $new = (string) $request->input('password', '');

        if (!password_verify($current, $me['password_hash'])) {
            $this->withError('Mevcut sifreniz hatali.');
            $this->redirect('/panel/profil');
        }
        $v = new Validator(['password' => $new], ['password' => 'Yeni sifre']);
        if (!$v->validate(['password' => 'required|min:8|max:72|confirmed'])) {
            Session::set('_errors', $v->firstErrors());
            $this->back('/panel/profil');
        }

        (new User())->update((int) $me['id'], ['password_hash' => Auth::hash($new)]);
        $this->withSuccess('Sifreniz basariyla guncellendi.');
        $this->redirect('/panel/profil');
    }
}
