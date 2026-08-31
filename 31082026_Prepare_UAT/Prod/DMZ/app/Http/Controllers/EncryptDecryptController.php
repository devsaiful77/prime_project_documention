<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncryptDecryptController extends Controller
{
    public function index(Request $request){
        $result = null;

        if ($request->filled('plainTextInput')) {
            $result = encrypt($request->plainTextInput);
        }

        if ($request->filled('encryptedTextInput')) {
            try {
                $result = decrypt($request->encryptedTextInput);
            } catch (\Exception $e) {
                $result = 'Invalid encrypted text';
            }
        }
        return view('encrypt-decrypt', compact('result'));
    }
}
