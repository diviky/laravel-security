<?php

declare(strict_types=1);

namespace Diviky\Security\Http\Controllers\Security;

use App\Http\Controllers\Controller as BaseController;
use App\Models\User;
use Diviky\Bright\Mail\Mailable;
use Diviky\Security\Models\LoginHistory;
use Diviky\Security\Models\Session;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    public function loadViewsFrom(): array
    {
        return [__DIR__];
    }

    public function index(): array
    {
        return [];
    }

    public function twofa($user_id = null): array
    {
        $g2fa = app('pragmarx.google2fa');
        $user_id = $user_id ?: user('id');

        $user = User::find($user_id);

        if ($this->isMethod('post')) {
            $task = $this->post('task');
            $user_id = $user->id;

            if ($task == 'verify') {
                $this->rules([
                    'code' => 'required',
                ]);

                $secret = $this->post('secret');
                $code = $this->post('code');

                if (strlen($secret) < 16) {
                    return [
                        'status' => 'ERROR',
                        'message' => 'Secret key Must be at least 16',
                    ];
                }

                $valid = $g2fa->verifyKey($secret, $code);

                if (! $valid) {
                    return [
                        'status' => 'ERROR',
                        'message' => 'Two factor Authentication FAILED.',
                    ];
                }

                return [
                    'status' => 'OK',
                    'message' => 'Two factor Authentication SUCCESS.',
                ];
            }

            $password = $user->password;
            if (! Hash::check($this->input('password'), $password)) {
                return [
                    'status' => 'ERROR',
                    'message' => __('Your current password didn\'t match.'),
                ];
            }

            if ($task == 'disable') {
                $user->google2fa_secret = null;
                $user->save();

                return [
                    'status' => 'OK',
                    'message' => 'Two factor Authentication DISABLED for your account.',
                    'reload' => true,
                ];
            }

            $this->rules([
                'secret' => 'required|min:16',
                'code' => 'required|min:4',
            ]);

            $secret = $this->post('secret');
            $code = $this->post('code');
            $valid = $g2fa->verifyKey($secret, $code);

            if (! $valid) {
                return [
                    'status' => 'ERROR',
                    'message' => 'Two factor Authentication FAILED. Try with new code.',
                ];
            }

            $user->google2fa_secret = $secret;
            $user->save();

            $qrcode = $g2fa->getQRCodeInline(
                $user->username,
                $user->email,
                $secret
            );

            $tags = [
                'row' => $user,
                'secret' => $secret,
                'url' => $qrcode,
            ];

            (new Mailable)
                ->subject(__('Two Factor Authentication enabled'))
                ->with($tags)
                ->prefix('security::emails.')
                ->markdown('security.twofa_enabled')
                ->deliver($user);

            return [
                'status' => 'OK',
                'message' => 'Two factor Authentication ENABLED for your account.',
                'reload' => true,
            ];
        }

        $enabled = false;

        if ($user->google2fa_secret) {
            $secret = $user->google2fa_secret;
            $enabled = true;
        } else {
            $secret = $g2fa->generateSecretKey();
        }

        $qrcode = $g2fa->getQRCodeInline(
            $user->username,
            $user->email,
            $secret
        );

        return [
            'user' => $user,
            'qrcode' => $qrcode,
            'secret' => $secret,
            'enabled' => $enabled,
        ];
    }

    public function verify2fa()
    {
        return redirect(URL()->previous());
    }

    public function logins(): array
    {
        $data = $this->all();

        $rows = LoginHistory::filter($data)
            ->where('user_id', user('id'))
            ->ordering($data, ['created_at' => 'desc'])
            ->paginate();

        $this->ajax('/security/logins');

        return [
            'rows' => $rows,
        ];
    }

    public function sessions(): array
    {
        $date = carbon();
        $date->subMinutes(config('session.lifetime'));
        $timestamp = $date->getTimestamp();
        $data = $this->all();

        $rows = Session::filter($data)
            ->where('user_id', user()->id)
            ->where('last_activity', '>', $timestamp)
            ->take(10)
            ->get();

        $session_id = session()->getId();

        $rows->transform(function ($row) use ($session_id) {
            $row->active = $session_id == $row->id;

            return $row;
        });

        $this->ajax('/security/sessions');

        return [
            'rows' => $rows,
        ];
    }

    public function delete($id)
    {
        $result = Session::destroy($id);

        return $this->deleted($result, 'session');
    }
}
