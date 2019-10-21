<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;

class College_search extends Controller
{
   public function search_college_name($name)
   {
         
         if (!empty($name)) 
         {

            $names = explode(" ", $name);
            $data=[];
            $where_name=[];
            foreach ($names as $key => $name) 
            {
               $where_name[]=['InstitutionList.InstitutionName', 'like', '%' . $name . '%'];
            }

            $answers = DB::table('InstitutionList')
            ->join('IC2017_AY', 'InstitutionList.InstitutionId', '=', 'IC2017_AY.UNITID')
            ->join('AveCGPA', 'InstitutionList.InstitutionId', '=', 'AveCGPA.InstitutionId')
            ->join('HD2017', 'InstitutionList.InstitutionId', '=', 'HD2017.UNITID')
            ->join('EFFY2017', 'InstitutionList.InstitutionId', '=', 'EFFY2017.UNITID')
            ->where('EFFY2017.EFFYLEV',1)
            ->where($where_name)
            ->take(5)
            ->select('InstitutionList.InstitutionId','InstitutionList.InstitutionName')
            ->get();
                
            return $answers;
         }


   }

   public function get_major()
   {
      $majors = DB::table('MajorsList')->distinct()->select('MajorTitle')->orderBy('MajorTitle', 'asc')->get();

    
      return json_encode($majors);
   }


   public function get_state()
   {
        $state = DB::table('tbl_state')->get();

         return json_encode($state);
   }

   public function get_state_cities(request $request)
   {
         $state=$request->state;
       $cities = DB::table('tbl_city')->where('state_code',$state)->get();

         return json_encode($cities);
   }


   public function get_hotel_city(request $request)
   {
      $name=$request->name;
      $id=DB::table('tbl_poi')->select('poiid_ppn')->where('poi_name',$name)->get();
      return json_encode($id);
   }

     public function get_cities()
   {
      
      $cities=DB::table('tbl_hotel_city')->select('city_id','city','state')->get();
      return json_encode($cities);
   }

   public function city_by_name()
   {

        $name=Input::get('term', false);
        if (!empty($name)) 
        {
        

            $names = explode("+", $name);
            $data=[];
            $where_name=[];
            foreach ($names as $key => $name) 
            {
               $where_name[]=['city', 'like', '%' . $name . '%'];
            }

            $cities=DB::table('tbl_hotel_city')->where($where_name)->select('city_id','city','state')
              ->take(10)->get();
            return json_encode($cities);

            // return $where_name;
        }
   }


   public function college_profie($id)
   {
   		$data=[];


			$smart_facts = DB::table('HD2017')
            ->join('EFFY2017', 'HD2017.UNITID', '=', 'EFFY2017.UNITID')
            ->where([['HD2017.UNITID',$id],['EFFY2017.EFFYLEV',1]])
            ->select('HD2017.INSTNM as name','HD2017.IALIAS as know_as', 'HD2017.CHFNM as president','HD2017.GENTELE as number','HD2017.WEBADDR as web_addr','HD2017.APPLURL as application_url','HD2017.FAIDURL as financial_aid_url','HD2017.ADDR as address', 'HD2017.CITY as city','HD2017.STABBR as state','HD2017.ZIP as zip','HD2017.LATITUDE as latitude','HD2017.LONGITUD as longitude' ,'EFFY2017.EFYTOTLT as total','EFFY2017.EFYTOTLM as totalmale','EFFY2017.EFYTOTLW as totalfemale')
            ->get()->toArray();
   		$data['smart_facts']=$smart_facts;
   			// admitted range == avg cgpa
   			$admission = DB::table('ADM2017')
            ->join('HD2017', 'ADM2017.UNITID', '=', 'HD2017.UNITID')
            ->join('IC2017', 'ADM2017.UNITID', '=', 'IC2017.UNITID')
            ->join('AveCGPA', 'ADM2017.UNITID', '=', 'AveCGPA.InstitutionId')
            ->where('ADM2017.UNITID',$id)
            ->select('ADM2017.ADMCON1 as gpa','ADM2017.ADMSSN as total_admitted', 'ADM2017.ENRLT as total_enrolled','HD2017.ADMINURL as admission_url','IC2017.APPLFEEU as app_fee','AveCGPA.AverageGPA as avg_gpa')
            ->get()->toArray();	
           $data['admission']=$admission; 


               $sat = DB::table('ADM2017')
                        ->where('UNITID',$id)
                     ->select('SATVR25 as critical_reading_25th','SATVR75 as critical_reading_75th', 'SATMT25 as math_score_25th','SATMT75 as math_score_75th')
                     ->get()->toArray();
               $data['SAT']=$sat;


               $act=DB::table('ADM2017')
                        ->where('UNITID',$id)
                     ->select('ACTCM25 as composite_25th','ACTCM75 as composite_75th', 'ACTEN25 as english_score_25th','ACTEN75 as english_score_75th','ACTMT25 as math_score_25th','ACTMT75 as math_score_75th')
                     ->get()->toArray();

               $data['ACT']=$act;

   	
  

   		  $college_living = DB::table('IC2017')

            ->join('EFFY2017', 'IC2017.UNITID', '=', 'EFFY2017.UNITID')
            ->where([['IC2017.UNITID',$id],['EFFY2017.EFFYLEV',1]])
            ->where('IC2017.UNITID',$id)
            ->select('IC2017.ALLONCAM as freshmen_required','IC2017.ROOMCAP as dormitory_capacity','IC2017.MEALSWK as meal_pre_week','IC2017.SLO5 as rotc','IC2017.LIBRES1 as library_services','IC2017.DISTCRS as distance_education','IC2017.CALSYS as calendar_system','EFFY2017.EFYTOTLT as total','EFFY2017.EFYTOTLM as totalmale','EFFY2017.EFYTOTLW as totalfemale')->get()->toArray();
            
            $data['college_living']=$college_living;



      $ncaa = DB::table('IC2017')
            ->where('IC2017.UNITID',$id)
            ->select('SPORT1 as for_football', 'SPORT2 as for_basketball','SPORT3 as for_baseball','SPORT4 as for_crosscountry_track')->get()->toArray();


         $data['NCAA']=$ncaa;



              $member_of = DB::table('IC2017')
            ->where('IC2017.UNITID',$id)
            ->select('ATHASSOC as NAA', 'ASSOC1 as NCAA','ASSOC2 as NAIA','ASSOC3 as NJCAA','ASSOC4 as NSCAA','ASSOC5 as NCCAA','ASSOC6 as other')->get()->toArray();

            $data['MEMBER']=$member_of;


   			$money_talk = DB::table('IC2017_AY')
            ->join('IC2017', 'IC2017_AY.UNITID', '=', 'IC2017.UNITID')
            ->join('SFA1617_P1', 'IC2017_AY.UNITID', '=', 'SFA1617_P1.UNITID')
            ->join('SFA1617_P2', 'IC2017_AY.UNITID', '=', 'SFA1617_P2.UNITID')
            ->where('IC2017_AY.UNITID',$id)
            ->select('IC2017_AY.TUITION2 as in_state_tuition','IC2017_AY.FEE2 as in_state_fee','IC2017_AY.TUITION3 as out_state_tuition','IC2017_AY.FEE3 as out_state_fee','IC2017_AY.CHG4AY3 as books_supplies','IC2017_AY.CHG6AY3 as other_expenses','IC2017.ROOMAMT as room_charge','IC2017.BOARDAMT as board_charge','IC2017.RMBRDAMT as combined_charge','SFA1617_P1.SCUGRAD as total_receiving','SFA1617_P1.SCUGFFN as full_time_receiving','SFA1617_P2.SCUGFFP as percent_receiving' ,'SFA1617_P1.UAGRNTN as total_receiving_grant','SFA1617_P1.UAGRNTA as avg_grant_award')->get()->toArray();
            $data['money_talk']=$money_talk;

            $net_price=DB::table('HD2017')
            ->where('UNITID',$id)
            ->select('NPRICURL as link')->get()->toArray();
            $data['net_price']=$net_price;	
   			
   		return $data;
   }

   public function search_college($name)
   {
      // $name=$request->name;

      $uni_id = DB::table('InstitutionList')
                          ->select('InstitutionId')
                              ->where('InstitutionName','like','%'.$name.'%')
                              ->get()->toArray();


      $ids=[];
      for ($i=0; $i <count($uni_id) ; $i++) 
      { 
         
          $id=$uni_id[$i]->InstitutionId;
          array_push($ids, $id);    
      }

      $answers = DB::table('InstitutionList')
            ->join('IC2017_AY', 'InstitutionList.InstitutionId', '=', 'IC2017_AY.UNITID')
            ->join('AveCGPA', 'InstitutionList.InstitutionId', '=', 'AveCGPA.InstitutionId')
            ->join('HD2017', 'InstitutionList.InstitutionId', '=', 'HD2017.UNITID')
            ->join('EFFY2017', 'InstitutionList.InstitutionId', '=', 'EFFY2017.UNITID')
            ->where('EFFY2017.EFFYLEV',1)
            ->whereIn('InstitutionList.InstitutionId', $ids)
            ->select('InstitutionList.InstitutionId as id','InstitutionList.InstitutionName as college_name','IC2017_AY.TUITION2 as in_state_tuition','IC2017_AY.TUITION3 as out_state_tuition','AveCGPA.AverageGPA as gpa','HD2017.CONTROL as  public_private','EFFY2017.EFYTOTLT as enrollment')
            ->get();

        
      foreach ($answers as $key => $value) 
      {
         $value=get_object_vars($value);
         $uni_id=$value['id'];
    
      
            $cipcode = DB::table('C2017_A')
                        ->join('MajorsList', 'C2017_A.CIPCode', '=', 'MajorsList.CIPCode')
                           ->select('MajorsList.MajorTitle')
                           ->where([['UNITID',$uni_id],['MAJORNUM',1],['AWLEVEL',5]])
                              ->get()->toArray();
            $major[]=$cipcode;     
      }

            $data=json_decode($answers);

            for ($i=0; $i <count($data) ; $i++) 
            { 
               
               $data[$i]->major=$major[$i];
            }
            return json_encode($data);
   }

   public function filter_search(request $request)
   {
      
         $data=$request->data;
            // return $data;
         $enroll='';
         $major='';
         $code_id='';
         $major=[];
         $final=[];
         $where_data=[];
         $where_in=[];

         if (!empty($data[0]['cost'])) 
         {
            $from=$data[0]['cost']['from'];
            $from=str_replace(['$', ','], '', $from);
            $from = (int)$from; 
            //$from=str_replace('$', '', $from);

            $to=$data[0]['cost']['to'];
            $to=str_replace(['$', ','], '', $to);
            $to = (int)$to;

             array_push($where_data, ['IC2017_AY.TUITION2', '>=', $from],['IC2017_AY.TUITION2', '<=', $to]);
         }
         if (!empty($data[0]['gpa'])) 
         {
            $from=$data[0]['gpa']['from'];
            $to=$data[0]['gpa']['to'];
            array_push($where_data, ['AveCGPA.AverageGPA', '>=', $from],['AveCGPA.AverageGPA', '<=', $to]);
         }
         if (!empty($data[0]['enrollment'])) 
         {
            $from=$data[0]['enrollment']['from'];
            $from=str_replace(',', '', $from);

            $to=$data[0]['enrollment']['to'];
            $to=str_replace(',', '', $to);

             array_push($where_data, ['EFFY2017.EFYTOTLT', '>=', $from],['EFFY2017.EFYTOTLT', '<=', $to],['EFFY2017.EFFYLEV',1]);
         }
         else
         {
            array_push($where_data,['EFFY2017.EFFYLEV',1]);
         }
     

         if (!empty($data[0]['major'])) 
         {
            $major=$data[0]['major'];

            $cipcode = DB::table('MajorsList')
                              ->select('CIPCODE')
                              ->Where([['CIPCODE','!=','0.0000'],['MajorTitle','like','%'.$major.'%']])
                              ->get()->toArray();
            array_push($where_data,['C2017_A.MAJORNUM',1],['C2017_A.AWLEVEL',5]);
            for ($i=0; $i < count($cipcode); $i++) 
            { 
               $code=$cipcode[$i]->CIPCODE;
               array_push($where_in,$code);
            }
         }
       

         if (!empty($data[0]['state'])) 
         {
            $state=$data[0]['state'];
            array_push($where_data, ['HD2017.STABBR', $state]);
         }

         if (!empty($data[0]['city'])) 
         {
             $city=$data[0]['city'];
            array_push($where_data, ['HD2017.CITY', $city]);
         }

         if (!empty($data[0]['preference'])) 
         {
            $preference=$data[0]['preference'];
               if ($preference==1) 
               {
                  // this is public
                    array_push($where_data, ['HD2017.CONTROL',1]);
                      
               }else
               {
                   array_push($where_data, ['HD2017.CONTROL', '!=' , 1],['HD2017.CONTROL', '!=' , -3]);
               }   
         }

         // return $where_data;

         
          // array_push($where_data,['C2017_A.MAJORNUM',1],['C2017_A.AWLEVEL',5]);
      if (!empty($where_in)) 
      {
         $answers = DB::table('C2017_A')
            ->join('AveCGPA', 'C2017_A.UNITID', '=', 'AveCGPA.InstitutionId')
            ->join('IC2017_AY', 'C2017_A.UNITID', '=', 'IC2017_AY.UNITID')
            ->join('HD2017', 'C2017_A.UNITID', '=', 'HD2017.UNITID')
            ->join('MajorsList', 'C2017_A.CIPCODE', '=', 'MajorsList.CIPCODE')
            ->join('EFFY2017', 'C2017_A.UNITID', '=', 'EFFY2017.UNITID')
            ->whereIn('C2017_A.CIPCODE',$where_in)
             ->where($where_data)
             ->groupBy('C2017_A.UNITID')
             ->groupBy('HD2017.INSTNM')
             ->groupBy('IC2017_AY.TUITION2')
             ->groupBy('IC2017_AY.TUITION3')
             ->groupBy('AveCGPA.AverageGPA')
             ->groupBy('HD2017.CONTROL')
             ->groupBy('EFFY2017.EFYTOTLT')
             ->groupBy('HD2017.CITY')
             ->groupBy('HD2017.STABBR')
            ->select('C2017_A.UNITID as id','HD2017.INSTNM as college_name','IC2017_AY.TUITION2 as in_state_tuition','IC2017_AY.TUITION3 as out_state_tuition','AveCGPA.AverageGPA as gpa','HD2017.CONTROL as public_private','EFFY2017.EFYTOTLT as enrollment','HD2017.CITY as city','HD2017.STABBR as state')
            ->paginate(20);

                
              for ($i=0; $i <count($answers); $i++) 
              { 
                $collegeid=$answers[$i]->id;
                 $cipcode = DB::table('C2017_A')
                        ->join('MajorsList', 'C2017_A.CIPCode', '=', 'MajorsList.CIPCode')
                           ->select('MajorsList.MajorTitle as major')
                           ->where([['UNITID',$collegeid],['MAJORNUM',1],['AWLEVEL',5]])
                           ->whereIn('C2017_A.CIPCODE',$where_in)
                              ->get()->toArray();  
               $answers[$i]->majors=$cipcode;
              }
              return $answers;
      }
      else
      {
         $answers = DB::table('C2017_A')
            ->join('AveCGPA', 'C2017_A.UNITID', '=', 'AveCGPA.InstitutionId')
            ->join('IC2017_AY', 'C2017_A.UNITID', '=', 'IC2017_AY.UNITID')
            ->join('HD2017', 'C2017_A.UNITID', '=', 'HD2017.UNITID')
            ->join('MajorsList', 'C2017_A.CIPCODE', '=', 'MajorsList.CIPCODE')
            ->join('EFFY2017', 'C2017_A.UNITID', '=', 'EFFY2017.UNITID')
            // ->whereIn('C2017_A.CIPCODE',$where_in)
             ->where($where_data)
             ->groupBy('C2017_A.UNITID')
             ->groupBy('HD2017.INSTNM')
             ->groupBy('IC2017_AY.TUITION2')
             ->groupBy('IC2017_AY.TUITION3')
             ->groupBy('AveCGPA.AverageGPA')
             ->groupBy('HD2017.CONTROL')
             ->groupBy('EFFY2017.EFYTOTLT')
             ->groupBy('HD2017.CITY')
             ->groupBy('HD2017.STABBR')
            ->select('C2017_A.UNITID as id','HD2017.INSTNM as college_name','IC2017_AY.TUITION2 as in_state_tuition','IC2017_AY.TUITION3 as out_state_tuition','AveCGPA.AverageGPA as gpa','HD2017.CONTROL as public_private','EFFY2017.EFYTOTLT as enrollment','HD2017.CITY as city','HD2017.STABBR as state')
            ->paginate(20);

            
              for ($i=0; $i <count($answers); $i++) 
              { 
                $collegeid=$answers[$i]->id;
                 $cipcode = DB::table('C2017_A')
                        ->join('MajorsList', 'C2017_A.CIPCode', '=', 'MajorsList.CIPCode')
                           ->select('MajorsList.MajorTitle as major')
                           ->where([['UNITID',$collegeid],['MAJORNUM',1],['AWLEVEL',5]])
                              ->get()->toArray();  
               $answers[$i]->majors=$cipcode;
              }
               return $answers;
      }

            

   }


}
