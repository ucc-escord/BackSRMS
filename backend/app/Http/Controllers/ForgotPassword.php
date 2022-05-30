<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\User;
use App\Admin;
use App\ProfessorAccount;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\MailForgotPassword;
use SendGrid;

use Illuminate\Support\Str;

class ForgotPassword extends Controller
{
    //
    public function createCodeForForgetPassword(Request $request){

        //user
        $email = $request->email;

        $user = User::where('email', $email)->limit(1)->count();

        $admin = Admin::where('email', $email)->limit(1)->count();

        $prof = ProfessorAccount::where('email', $email)->limit(1)->count();




        if($user === 1){
            $pin = Str::random(64);
            
            DB::table('users')
            ->where('email', $email)
            ->update(['code' => $pin,
        ]);

      //  return response()->json(['message'=>'code created successfully Student']);

        return  $this->email($email,$pin);
            
        }

        //staff
        

        if($admin === 1){
            $pin = Str::random(64);

            DB::table('admins')
            ->where('email', $email)
            ->update(['code' => $pin,
        ]);

      //  return response()->json(['message'=>'code created successfully Admin']);

        return  $this->email($email,$pin);

             }

        

        if($prof === 1){
        
             $pin = Str::random(64);
                    DB::table('professor_accounts')
                    ->where('email', $email)
                    ->update(['code' => $pin,
                 ]);
              //  return response()->json(['message'=>'code created successfully Professor']);
        
                return  $this->email($email,$pin);
        

             }
            
        
           if($user === 0 || $prof === 0 || $admin === 0 ) {
                return response()->json(['message' => '1']);

             } 
   
    }

    //$sendMail, $code
    public function email($sendMail, $code){

         $content = "This is your forget password code: " . $code;
     
        $data = [
            'subject' => 'UCC ESCORD FORGET PASSWORD',
            'email' => $sendMail,
            'content' => $content
          ];

        Mail::send([],$data, function($message) use ($data) {
            $message->to($data['email'])
            ->from('uccscholasticmanagement@gmail.com')
            ->subject($data['subject'])
            ->setBody($data['content']);
          });

    


        return response()->json(['message' => 'Email succeded']);

   //     Mail::to('uccscholasticmanagement@gmail.com')->send(new MailForgotPassword($message));

     /*    $data['name'] = "This is your forget password code :" . $code;
 
        Mail::send('mail', $data, function($message) {
 
            $message->to('uccscholasticmanagement@gmail.com', 'Receiver Name')
 
                    ->subject('Forgot Password ESCORD(UCC-SRMS)');
        });
  */
      
       // return response()->json(['message'=>$sendMail,$code]);

     //   $emailContent = $validated['senderMsg'];
   /*    $emailContent = 'This is the code of your account : ' . $code;

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("ucc.escord@gmail.com", "UCC ESCORD FORGET PASSWORD CODE");
        $email->setSubject("ESCORD UCC CONCERN");
        $email->addTo($sendMail, '');
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html",  $emailContent
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }     */

    }


    //UPDATE PASSWORD

    public function UpdatePassword(Request $request){


        $email = $request->email;
        $password = $request->password;
        $code =$request->code;

        $user = User::where('email', $email)->where('code',$code)->limit(1)->count();

        $admin = Admin::where('email', $email)->where('code',$code)->limit(1)->count();

        $prof = ProfessorAccount::where('email', $email)->where('code',$code)->limit(1)->count();




        if($user === 1){
         //   $pin = Str::random(64);
            
            DB::table('users')
            ->where('email', $email)->where('code',$code)
            ->update(['password' => Hash::make($request->password),
            'code'=>null,
        ]);

       return response()->json(['message'=>'Success New Password']);

       // return  $this->email($email,$pin);
            
        }

        //staff
        

        if($admin === 1){
            $pin = Str::random(64);

            DB::table('admins')
            ->where('email', $email)->where('code',$code)
            ->update(['password' => Hash::make($request->password),
            'code'=>null,
        ]);

      //  return response()->json(['message'=>'code created successfully Admin']);

      return response()->json(['message'=>'Success New Password']);
       

             }

        

        if($prof === 1){
        
             $pin = Str::random(64);
                    DB::table('professor_accounts')
                    ->where('email', $email)->where('code',$code)
                    ->update(['password' => Hash::make($request->password)
                    , 'code'=>null,
                 ]);

       return response()->json(['message'=>'Success New Password']);

              //  return response()->json(['message'=>'code created successfully Professor']);
        
               
        

             }
            

    }
       
            
    





        //admin
    }







