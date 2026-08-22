<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use App\ProductType;
use App\CustomerInterfaceAPI;

class CIBackendController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:superadmin|admin|adminActivity|true|ci_api']);
        parent::__construct();
    }

    public function ci_api_index(Request $request)
    {
        $title = "CI API List";
        $title_for_layout = "CI API List";
        $cifModelName = new CustomerInterfaceAPI;
        $tblData = array();
        $dataObj = $cifModelName->select("id", "name", "endpoint", "request_body", "product_type", "status", "date_format",
                DB::raw("CASE WHEN status = 1 THEN 'Active' WHEN status = 0 THEN 'Inactive' ELSE 'Invalid' END AS status_name"))
           /* ->with('productType')*/
            ->orderBy("id", "DESC")
            ->get();
        if (!empty($dataObj)) {
            $tblData = $dataObj->toArray();
        }
        return view('CustomerInterfaceAPI.list', compact('title', 'title_for_layout', 'tblData', 'dataObj'));
    }

    public function ci_api_create(Request $request)
    {
        $title = "Create CI API Request";
        $title_for_layout = "Create CI API Request";
        $productTypes = ProductType::select("id", "name")
            ->where("status", 1)
            ->orderBy("id", "ASC")
            ->get();
        return view('CustomerInterfaceAPI.add', compact('title', 'title_for_layout', 'productTypes'));
    }

    public function ci_api_store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'product_type' => 'required',
            'endpoint' => 'required',
            'request_body' => 'required',
            'date_format' => 'required',
        ]);
        $cifModelName = new CustomerInterfaceAPI;
        if ($request->isMethod('post')) {
            $cifModelName->name = $request->get('name');
            $cifModelName->product_type = $request->get('product_type');
            $cifModelName->endpoint = $request->get('endpoint');
            $cifModelName->request_body = trim(preg_replace('/\s\s+/', ' ', $request->get('request_body')));
            $cifModelName->date_format = $request->get('date_format');;
            if ($cifModelName->save()) {
                flash('CI API has been stored successfully', 'success');
                return redirect('/ci_apis/list');
            } else {
                flash('Failed to insert data', 'danger');
                return redirect()->back();
            }
        }
    }

    public function ci_api_edit(Request $request, $id = null)
    {
        $title = "Edit CI API Request";
        $title_for_layout = 'Edit CI API Request';
        $cifModelName = new CustomerInterfaceAPI;
        $dataForView = $cifModelName->where('id', decrypt($id))->first();
        if ($dataForView->status == 0) {
            abort(403, 'Edit Not Allowed !!!');
        }
        $productTypes = ProductType::select("id", "name")
            ->where("status", 1)
            ->orderBy("id", "ASC")
            ->get();
        return view('CustomerInterfaceAPI.edit', compact('title', 'title_for_layout', 'dataForView', 'productTypes'));
    }

    public function ci_api_update(Request $request, $id = null)
    {
        $this->validate($request, [
//            'name' => 'required',
            'product_type' => 'required',
            'endpoint' => 'required',
//            'request_body' => 'required',
//            'date_format' => 'required',
        ]);
        $cifModelName = new CustomerInterfaceAPI;
        if ($request->isMethod('post')) {
            $cifModelName = $cifModelName->where('id', decrypt($id))->first();
            $cifModelName->name = $request->get('name');
            $cifModelName->product_type = $request->get('product_type');
            $cifModelName->endpoint = $request->get('endpoint');

            /*  $cifModelName->request_body = trim(preg_replace('/\s\s+/', ' ', $request->get('request_body')));*/

//            $cifModelName->date_format = $request->get('date_format');;
            if ($cifModelName->save()) {
                flash('CI API has been updated successfully', 'success');
                return redirect('/ci_apis/list');
            } else {
                flash('Failed to update data', 'danger');
                return redirect()->back();
            }
        }
    }

    public function ci_api_status(Request $request, $id = null, $status = null)
    {
        $data = CustomerInterfaceAPI::find(decrypt($id));
        if (!empty($data)) {
            $data->update(['status' => $request->status]);
            if ($request->status == 1) {
                flash('CI API has been activated successfully', 'success');
            } else {
                flash('CI API has been inactivated !!', 'warning');
            }
        } else {
            flash('Not Found', 'danger');
        }
        return redirect()->back();
    }



}
