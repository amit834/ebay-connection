<?php

namespace Wave\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Wave\Announcement;
use Wave\Connection;

class CommunicationController extends Controller
{
    public function index()
    {
        $ebay_records = Connection::where('type','ebay')->first();
        $amazon_records = Connection::where('type','amazon')->first();
        $google_records = Connection::where('type','google')->first();
        $all_records = Connection::orderby('id','DESC')->get();
        return view('theme::communications.index', compact('ebay_records','amazon_records','google_records','all_records'));
    }
    
    //function for update or create communications
    public function create_update_connections(Request $request){
        //update or create data
        $records = Connection::updateOrCreate(
            [
               'type'   => $request->type,
            ],
            [
               'app_id' => $request->app_id,
               'dev_id' => $request->dev_id,
               'client_secret' => $request->client_secret,
               'api_uri' => $request->api_uri,
               'api_auth_uri'  => $request->api_auth_uri,
               'type' => $request->type,
            ],
        );
        //check it is created or updated or not
        if ($records) {
            return redirect()->back()->with('success', 'Connection Saved Successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to save record.');
        }
    }

    //function for change status
    public function change_connection_status(Request $request){
        //Get Request
        $id =  $request->id;
        //Update status
        Connection::where('is_active', 'enable')->OrWhere('is_active', 'disable')->update(['is_active' => 'disable']);
        //Update Status with use id
        $is_updated = Connection::where('id', $id)->update(['is_active' => 'enable']);
        if($is_updated){
            echo '<p style="color:green;">Connection Switched Succesfully.</p>';
            echo "<script>
                setTimeout(function() {
                    window.location.href = '';
                }, 3000);
            </script>";
        } else {
            echo '<p style="color:red;">Oops something went wrong!</p>';
        }     
    }
}