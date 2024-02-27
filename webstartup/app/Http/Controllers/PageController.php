<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectsModel;
use App\Models\ProjectContenModel;
use App\Models\User;
use App\Models\InvoiceItemsModel;
use App\Models\InvoiceModel;
use App\Models\QuoteItemsModel;
use App\Models\QuoteModel;
use App\Models\OrderModel;
use Redirect;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}");
        }
        return abort(404);
    }
     public function create_page(Request $request)
    {
        $role=auth()->user()->role;
        $project_id=$request->project_id;
        $myids=$this->getLeadIDs($project_id);
        $project=ProjectsModel::join('users','users.id','=','projects.user_id')->where('project_id','=',$project_id)->first();  
        $contenttext=ProjectContenModel::where('project_id','=',$project_id)->where('content_type','=','text')->get();
         $contentfiles=ProjectContenModel::where('project_id','=',$project_id)->where('content_type','<>','text')->get();
        return view('pages.create_brief',compact(['project','contenttext','contentfiles','role','myids']));
    }
    public function create_project(Request $request)
    {
        $project_name=$request->project_name;
        $project_description=$request->project_description;        
        $user_id=auth()->user()->id;
        $proObj=new ProjectsModel();
        $proObj->project_name=$project_name;
        $proObj->project_description=$project_description;
        $proObj->user_id=$user_id;
        $proObj->entered_by=auth()->user()->email;
        if($proObj->save())
        {
             return redirect()->back()->with('success','New Project / Design successfully submitted');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to submit project / design']);
        }
    }
public function upload(Request $request)
    {
        $project_description=$request->project_description;
        $project_id=$request->project_id;
        $user_id=auth()->user()->id;
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,doc,docx,pdf,txt|max:20480', // Example validation rules
        ]);
        if(!empty($project_description))
        {
              $proObj=new ProjectContenModel();
            $proObj->project_id=$project_id;
            $proObj->content_name=$project_description;
            $proObj->content_type="text";
            $proObj->entered_by=$user_id;        
            $proObj->save();
        }
   
        if ($request->hasFile('files'))  {      

            $files = $request->file('files');
            $cc=count($files);
        foreach($files as $file){
            $proObj=new ProjectContenModel();
            $random_num=rand(1,10000);
                $filename = $random_num.$file->getClientOriginalName();
                $file->storeAs('uploads', $filename);          
            $extension = $file->getClientOriginalExtension();
            $proObj->project_id=$project_id;
            $proObj->content_name=$filename;
            $proObj->content_type=$extension;
            $proObj->entered_by=$user_id;  
            $proObj->random_number=$random_num;        
            $proObj->save();
             }
                  return redirect()->back()->with('success','Details uploaded successfully.');
         
        }

       return Redirect::back()->withErrors(['msg' => 'Failed to upload']);
    }
    public function projects()
    {
        $role=auth()->user()->role;
         $user_id=auth()->user()->id;
        if($role=="Admin")
        {
$projects=ProjectsModel::paginate(10);  
}
else
{
    $projects=ProjectsModel::join('users','users.id','=','projects.user_id')->where('projects.user_id','=',$user_id)->orderBy('project_id', 'DESC')->paginate(10); 
    }
    return view('pages.projects',compact(['projects','role']));
}
public function getLeadIDs($project_id)
    { 
      $myorder=OrderModel::where('lead_id','=',$project_id)->get();
      $myquote=QuoteModel::where('lead_id','=',$project_id)->get();
      $myinvoice=InvoiceModel::where('lead_id','=',$project_id)->get();
    
      return array('myinvoice'=>$myinvoice,'myquote'=>$myquote,'myorder'=>$myorder);
     
    }
}





