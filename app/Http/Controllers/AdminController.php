<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Board;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request){
            //validate request
            $this->validate($request,[
                'tagName'=> 'required'
            ]);
            return Tag ::create([
                'tagName' => $request->tagName
            ]);
    }
    public function editTag(Request $request){
    //validate request
    $this->validate($request,[
        'tagName'=> 'required',
        'id'=> 'required'
    ]);
    return Tag ::where('id',$request->id)->update([
        'tagName' => $request->tagName
    ]);
    
    }
    public function deleteTag(Request $request){
    //validate request
    $this->validate($request,[
        
        'id'=> 'required'
    ]);
    return Tag ::where('id',$request->id)->delete();
    
    }
    public function getTag(){
    return Tag::orderBy('id','desc')->get();
    }
    public function showModal(){
        return Tag::orderBy('id','desc')->get();
    }
    public function upload(Request $request){
        $this->validate($request,[
        
            'file'=> 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName );
        return $picName;
    }
    public function deleteImage(Request $request){
        $fileName = $request->imageName;
    $this->deleteFileFromServer($fileName);
        return 'done';
    }
    public function deleteFileFromServer($fileName,  $hasFullPath = false){
        if(!$hasFullPath){
            $filePath = public_path().'/uploads/'.$fileName;
        }
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }

    /////////////////보드/////////////////////
    public function addBoard(Request $request){
        //validate request
        $this->validate($request,[
            'title'=> 'required',
            'content'=> 'required',
            'contentImage'=> 'required'
        ]);
        return Board ::create([
            'title' => $request->title,
            'content' => $request->content,
            'contentImage' => $request->contentImage
        ]);
    }
    public function getBoard(){
    return Board::orderBy('id','desc')->get();
    }
    public function editBoard(Request $request){
    //validate request
    $this->validate($request,[
        'title'=> 'required',
        'content'=> 'required',
        'contentImage'=> 'required'
    ]);
    return Board ::where('id',$request->id)->update([
        'title' => $request->title,
        'content' => $request->content,
        'contentImage' => $request->contentImage
    ]);
    }
    public function deleteBoard(Request $request)
    {
    // first delete the original file from the server
    $this->deleteFileFromServer($request->iconImage);
    // validate request
    $this->validate($request, [
        'id' => 'required',
    ]);
    return Board::where('id', $request->id)->delete();
    }

    ///////////////유저///////////
 public function createUser(Request $request){
         //validate request
         $this->validate($request,[
            'fullName'=> 'required',
            'email'=> 'bail|required|email|unique:users',
            'password'=> 'bail|required|min:6',
            'userType'=> 'required'
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'userType' => $request->userType,
        ]);
        return $user;
        
    }


    public function editUser(Request $request)
    {
        // validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType,
        ];
        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }

    public function getUsers()
    {
        return User::where('userType','!=','User')->get();
    }


}
