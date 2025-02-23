<?php

declare(strict_types=1);

namespace Diviky\Security\Http\Controllers\Security;

use App\Http\Controllers\Controller as BaseController;
use App\Models\User;
use Diviky\Bright\Attributes\ViewPaths;
use Diviky\Bright\Mail\Mailable;
use Diviky\Security\Models\LoginHistory;
use Diviky\Security\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

#[ViewPaths([__DIR__])]
class Controller extends BaseController
{
    public function index(): array
    {
        return [];
    }

    public function twofa(Request $request, $user_id = null): array
    {
        $fa = app('pragmarx.google2fa');
        $user_id = $user_id ?: user('id');

        $user = User::find($user_id);

        if ($request->isMethod('post')) {
            $task = $request->post('task');
            $user_id = $user->id;

            if ($task == 'verify') {
                $request->validate([
                    'code' => 'required',
                ]);

                $secret = $request->post('secret');
                $code = $request->post('code');

                if (strlen($secret) < 16) {
                    return [
                        'status' => 'ERROR',
                        'message' => 'Secret key Must be at least 16',
                    ];
                }

                $valid = $fa->verifyKey($secret, $code);

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

            $request->validate([
                'password' => 'required',
            ]);

            $password = $user->password;

            if (! Hash::check($request->post('password'), $password)) {
                return [
                    'status' => 'ERROR',
                    'message' => __('Your current password didn\'t match.'),
                ];
            }

            if ($task == 'disable') {
                $user->two_factor_secret = null;
                $user->two_factor_confirmed_at = null;

                $user->save();

                return [
                    'status' => 'OK',
                    'message' => 'Two factor Authentication DISABLED for your account.',
                    'reload' => true,
                ];
            }

            $request->validate([
                'secret' => 'required|min:16',
                'code' => 'required|min:4',
            ]);

            $secret = $request->post('secret');
            $code = $request->post('code');
            $valid = $fa->verifyKey($secret, $code);

            if (! $valid) {
                return [
                    'status' => 'ERROR',
                    'message' => 'Two factor Authentication FAILED. Try with new code.',
                ];
            }

            $user->two_factor_secret = $secret;
            $user->two_factor_confirmed_at = now();

            $user->save();

            $qrcode = $fa->getQRCodeInline(
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

        if ($user->two_factor_secret) {
            $secret = $user->two_factor_secret;
            $enabled = true;
        } else {
            $secret = $fa->generateSecretKey();
        }

        $qrcode = $fa->getQRCodeInline(
            $user->user_id,
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
