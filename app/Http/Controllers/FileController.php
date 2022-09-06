<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Validator;
use App\Models\Files;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $isAuth = $this->checkUserAuthentication();
        if($isAuth['return']){
            $user = $isAuth['resp'];
            $userFiles = Files::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(10);
            return response()->json(['user_files'=> $userFiles], 200);
        }else{
            return response()->json($isAuth['resp'],422);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isAuth = $this->checkUserAuthentication();
        if($isAuth['return']) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:pdf|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 422);
            }

            $file = $request->file('file');
            $fileDisplayName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $hashName = uniqid(rand(), false) . '.' . $fileExtension;
            $userId = Auth::user()->id;

            $filePath = public_path(Config::get('const.files.pdf'));
            if ($file->move($filePath, $hashName . '.' . $fileExtension)) {
                if (File::exists($filePath . '/' . $hashName . '.' . $fileExtension)) {
                    $data = [
                        'display_name' => explode('.pdf', $fileDisplayName)[0],
                        'extension' => $fileExtension,
                        'size' => $fileSize,
                        'hash_name' => $hashName,
                        'user_id' => $userId
                    ];
                    if (Files::create($data)) {
                        return response()->json([
                            'message' => 'File uploaded with success.',
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => 'Something went wrong, file not uploaded.',
                    ], 422);
                }
            } else {
                return response()->json('message', 'Upload fail.');
            }
        }else{
            return response()->json($isAuth['resp'],422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkUserAuthentication()
    {
        if(Auth::check()){
            return ['return'=>true, 'resp'=>Auth::user()];
        }else{
            return ['return'=> false, 'resp' => response()->json(['message'=>'This action is unauthorized', 'code'=>403])];
        }
    }
}
