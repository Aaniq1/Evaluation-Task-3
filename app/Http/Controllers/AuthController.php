<?php

namespace App\Http\Controllers;

use App\Mail\Verifymail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $user= new User;
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->password=Hash::make($request->input('password'));
        $verification_token=base64_encode($request->input('name'));
        $user->verification_token=base64_encode($request->input('name'));
        if(User::Where('email',$user->email)->first())
        {
            return response()->json(["User Already Register"],400);
        }
        $user->save();
        
        $url=url('emailConfirmation/'.$request->input('email').'/'.$verification_token);
        Mail::to($request->input('email'))->send(new Verifymail($request->input('email'),$url));

        return response()->json(["User registered Successfully"],200);



        

        }

    function login(Request $request)
    {
        if(!User::Where('email',$request->input('email'))->first())
        {
            return response()->json(["Email Does Not Exist"],404);
        }
        $verify=Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')]);
        if ($verify) {
            $user_data['user_name']=auth()->user()->name;
            $user_data['email']=auth()->user()->email;
            $secret_key="P0551BL3";
        
                
            $iss = "localhost";
            $iat = time(); 
            $nbf = $iat+10; 
            $exp = $iat+1800; 
            $aud = "Merchant"; 

            $payload_info= array(
                "iss" =>$iss,
                "iat" =>$iat, 
                "nbf" =>$nbf,
                "exp" =>$exp, 
                "aud" =>$aud, 
                "data" =>$user_data
                );
            
            $jwt = JWT::encode($payload_info, $secret_key, 'HS256');
            
            $data['success']='User Login Successfully';
            $data['Auth_token']=$jwt;
            $data['Auth_token_type']='Bearer';
            
            $user=User::where('email',$user_data['email'])->first();
            $user->Auth_token=$jwt;
            if ($user->update()) {
                return response()->json($data,200);    
            } 
        }
        
    
    }
    public function logout(Request $request) {
        
        $token =$request->bearerToken();
        $data_decoded=JWT::decode($token,new key("P0551BL3",'HS256')  );
        $user=User::where("email",$data_decoded->data->email)->first();
  
        if ($user['email']=$data_decoded->data->email)
        {
            $user->Auth_token=Null;
            $user->save();
            $data['data']="logout successfully";
            return response()->json($data,200);              
        }
  
  
   
          
  
  
      }
  
    
  

}