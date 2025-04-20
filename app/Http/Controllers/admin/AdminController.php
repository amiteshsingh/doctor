<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Session;
use DB;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    use AuthenticatesUsers;


	public function dashboard()
	{
		$user = Auth::user();
		$userRole = UserRole::where('user_id', $user->id)->first();
		
		if (!$user || !$userRole || $userRole->role !== 'admin') {
			return redirect('/');
		}

		return view('admin.dashboard');
	}

      

    public function logout(Request $request) {

		#$cur_date = DB::select("select NOW() as date");
		#// print_r($cur_date); //exit();
		#if(session('Role_ID')){
		#	if($request->session()->has('User_ID')){
		#		$loginData['Logout_Time'] = $cur_date[0]->date;
		#		$loginData['Login_Status'] = $request->type;
		#		DB::table("Employee_Logs")->where("Auto_ID", "=",  session('employee_current_id'))->update($loginData);
		#	}
		#}

		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect('/');
	}

	public function editProfile(Request $request, $id){
		
		if(empty(Session::get('user_id'))){
			return redirect('/');
		}
		$user['user'] = DB::table('users')->where('id', '=', $id)->get();
		return view('admin/edit',$user);
	}

	public function updateProfile(Request $request, $id){

		$data=$request->all();

		//print_r($data); die;

        $request->validate([
            'first_name' => 'required',
			'email' => [
                'required',
                Rule::unique('users', 'email')->ignore($id, 'id')
            ],
            'phone_no' => [
                'required',
                Rule::unique('users', 'phone_no')->ignore($id, 'id')
			],
			'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
		]);
		$attachment='';
		if($request->file('profile_photo')){
			$attachment = $request->file('profile_photo');
			$Original_File_Name = $attachment->getClientOriginalName();       
			$attachmentName = time().'-'. preg_replace('/\s _+/', '_', $attachment->getClientOriginalName());
			// Replaces all spaces with hyphens.
			$attachmentName = str_replace(' ', '-', $attachmentName);
			// Removes special chars.
			$attachmentName = preg_replace('/[^A-Za-z0-9\-.]/', '', $attachmentName); 
		}else{ 
			$attachmentName='';
			$Original_File_Name='';
			$filePattern='';
		}
		if($attachment){
			$s3 = Storage::disk('s3');
			$filePath = '/images/profile/' . $attachmentName;
			$path = $s3->put($filePath, file_get_contents($attachment), 'public');
		}
		$association = User::find($id); 
		$association->first_name = $data['first_name'];
        //$association->last_name = $data['last_name'];
        $association->email = $data['email'];
		$association->phone_no = $data['phone_no'];
		if($data['password']){
			//$association->password = Hash::make($data['password'], [ 'rounds' => 10]);

			$jsondata = [
                'password' => $data['password'],
                'confirm_password' => $data['password']
            ];
			$api_url=  env('API_URL');
            // Make the POST request
            $responsePassword = Http::withHeaders([
                'Authorization' => Session::get('API_TOKEN'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($api_url. '/api/common/generate-password', $jsondata);
			
			if($responsePassword['errStatus'] == false ){
				$association->password = $responsePassword['data'][0]['new_password'];
				//print($responsePassword); die;
			}
			
			

		}
		if(!empty($attachmentName) && $attachmentName !=""){
			$association->profile_photo = $attachmentName;
		}
		$association->save();
		if(isset($association->id) && !empty($attachmentName) && $attachmentName !=""){
			$request->session()->forget('profile_photo');
			$request->session()->put('profile_photo',$attachmentName);
		}
		$request->session()->flash('msg','Profile update successfully.');	
        return redirect('admin/edit/'.$id);  
	}
}

?>
