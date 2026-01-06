<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function profile(Request $request)
    {
        $data = [
            'auth' => $request->auth,
        ];

        return view('features.profile.profile-page', $data);
    }

    public function beranda(Request $request)
    {
        $data = [
            'auth' => $request->auth,
        ];

        return view('features.beranda.beranda-page', $data);
    }

    public function hakAkses(Request $request)
    {
        $data = [
            'auth' => $request->auth,
        ];

        return view('features.hak-akses.hak-akses-page', $data);
    }

    // Tim
    // ------------------------------
    public function tim(Request $request)
    {
        $data = [
            'auth' => $request->auth,
        ];

        return view('features.tim.tim-page', $data);
    }
}
