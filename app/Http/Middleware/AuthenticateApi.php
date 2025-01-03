<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Http\sources\User\User;

class AuthenticateApi extends Middleware{

    protected function authenticate($request, array $guards){
        $user = new User($request);

        session('locationExceptions')->setIpExceptions([
            'list of IP addresses to exclude from token verification'
        ]);

        $user->setExceptions([
            session('locationExceptions')->isUrlExceptions(),
            session('locationExceptions')->isIpExceptions(),
            stripos($request->url(), '.../fio'),
            stripos($request->url(), '.../fio'),
        ]);

        $user->checkToken();
        $user->getRight();

        return session(['user'=>$user]);
    }
}