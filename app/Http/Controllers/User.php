<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User_model;
use DB;
use Mail;

class User extends Controller
{
    public function register(request $request)
    {
    	$user = new User_model;
    	$token=sha1(time());
        
        $user->first_name=$request->first_name;
        $user->middle_initial=$request->middle_initial;
        $user->last_name=$request->last_name;
        $user->preferred_name=$request->preferred_name;
        $user->email=$request->email;
        $user->password=md5($request->password);
        $user->news_letter=$request->news_letter;
        $user->birthday=$request->birthday;
        $user->graduation_date=$request->graduation_date;
        $user->zip_code=$request->zip_code;
        $user->address1=$request->address1;
        $user->address2=$request->address2;
        $user->city=$request->city;
        $user->state=$request->state;
        $user->country=$request->country;
        $user->international=$request->international;
        $user->major1=$request->major1;
        $user->major2=$request->major2;
        $user->major3=$request->major3;
        $user->school_name_code=$request->school_name_code;
        $user->gpa=$request->gpa;
    	$user->user_token=$token;

    	$user->save();
      $id=$user->id;

      $data=[];
            $data['id']=$id;
            $data['first_name']=$request->first_name;
            $data['last_name']=$request->last_name;
        $data['token']=$token;
        return json_encode($data);
       
    }

    public function login(request $request)
    {
    	
    	$email=$request->email;
    	$password=md5($request->password);


    	$token=sha1(time());
    	DB::table('tbl_user')->where('email', $email)->update(['user_token' => $token]);
    	$user = DB::table('tbl_user')
    	->where([['email', $email],['password', $password],])
    	->get()->toArray();
    
    	if (!empty($user)) 
    	{
            $data=[];
            $data['id']=$user[0]->id;
            $data['first_name']=$user[0]->first_name;
            $data['last_name']=$user[0]->last_name;
    		$data['token']=$user[0]->user_token;
    		return json_encode($data);
    	}
    	else
    	{
    		return "false";
    	}
    }

    public function add_favorite(request $request)
    {
        $user_id=$request->user_id;
        $uni_id=$request->uni_id;

        DB::table('tbl_favorite_college')->insert(
            ['user_id' => $user_id, 'UNITID' => $uni_id]
            );
        return "College is Adeed to your Favorites";
    }

    public function remove_favorite(request $request)
    {
        $user_id=$request->user_id;
        $uni_id=$request->uni_id;

        DB::table('tbl_favorite_college')->where([['user_id',$user_id],['UNITID',$uni_id]])->delete();
        return "College is Deleted From your Favorites";
    }

    public function get_user_data(request $request)
    {
        $id=$request->id;
        
               $data=DB::table('tbl_user')->where('id',$id)->get();
               return json_encode($data);


    }

    public function update_user_data(request $request)
    {
        $id=$request->id;
         DB::table('tbl_user')
            ->where('id', $id)
          ->update([
           'first_name'=>$request->first_name,
        'middle_initial'=>$request->middle_initial,
        'last_name'=>$request->last_name,
        'preferred_name'=>$request->preferred_name,
        'password'=>md5($request->password),
        'news_letter'=>$request->news_letter,
        'birthday'=>$request->birthday,
        'graduation_date'=>$request->graduation_date,
        'zip_code'=>$request->zip_code,
        'address1'=>$request->address1,
        'address2'=>$request->address2,
        'city'=>$request->city,
        'state'=>$request->state,
        'country'=>$request->country,
        'international'=>$request->international,
        'major1'=>$request->major1,
        'major2'=>$request->major2,
        'major3'=>$request->major3,
        'school_name_code'=>$request->school_name_code,
        'gpa'=>$request->gpa
            ]);

          return "Data is updated..!";

    }

    public function get_favorite_college(request $request)
    {
         $id=$request->id;
               $data=DB::table('tbl_favorite_college')
               ->join('InstitutionList', 'tbl_favorite_college.UNITID', '=', 'InstitutionList.InstitutionId')
               ->join('HD2017', 'tbl_favorite_college.UNITID', '=', 'HD2017.UNITID')
               ->select('InstitutionList.InstitutionName as college_name','tbl_favorite_college.UNITID as college_id','HD2017.CITY as city')
               ->where('tbl_favorite_college.user_id',$id)
               ->orderBy('tbl_favorite_college.id','DESC')
               ->get();
               return json_encode($data);
    }

    public function user_favorite_college(request $request)
    {
        $id=$request->id;
        $data=DB::table('tbl_favorite_college')
                ->select('UNITID')
               ->where('user_id',$id)
               ->get();
               return $data;

    }

    public function add_favorite_articles(request $request)
    {
           $user_id=$request->user_id;
           $article_id=$request->article_id;

        DB::table('tbl_favorite_article')->insert(
            ['user_id' => $user_id, 'article_id' => $article_id]
            );
        return "Article is Adeed to your Favorites";
    }

       public function user_favorite_articles(request $request)
    {
        $id=$request->id;
        $data=DB::table('tbl_favorite_article')
                ->select('article_id')
               ->where('user_id',$id)
               ->orderBy('id','DESC')
               ->get();
               return $data;

    }

      public function remove_favorite_article(request $request)
    {
        $user_id=$request->user_id;
        $article_id=$request->article_id;

        DB::table('tbl_favorite_article')->where([['user_id',$user_id],['article_id',$article_id]])->delete();
        return "Article is Deleted From your Favorites";
    }

    public function email_check(request $request)
    {
        $email=$request->email;
        $data=DB::table('tbl_user')
               ->where('email',$email)
               ->count();
        if ($data>0) 
        {
            return "true";
        }else
        {
            return "false";
        }

    }

    public function user_email(request $request)
    {
        $fromPage = $request->fromPage;
        $email='';
        $subject='';
       if($fromPage == "platform"){
           $email="info@smartcollegevisit.com";
           $subject="Platform Form Submission";
       }
       else if($fromPage == "softlaunch"){
           $email="kelly@smartcollegevisit.com";
           $subject="Soft Launch Form Submission";
       }
       else{
           $email="campuschat@smartcollegevisit.com";
           $subject="Other Form Submissions";
       }

              $data = array(
             'first_name'=>$request->first_name,
             'last_name'=>$request->last_name,
             'email'=>$request->email,
             'phone'=>$request->phone,
             'contact_method'=>$request->contact_method,
           );

             // Mail::send('mail', $data, function($message)use($email,$subject){
             //    $message->to($email)->subject($subject);
             //       // from is same email we set in .env file
             //    $message->from('info@smartcollegevisit.com','SCV');
             // });


             Mail::send('mail', $data, function($message)use($email,$subject){
                $message->to($email);
                $message->subject($subject);
                   // from is same email we set in .env file
                $message->from('info@smartcollegevisit.com','SCV');
             }); 

             return "Email Sent.!";
            
    }
}
