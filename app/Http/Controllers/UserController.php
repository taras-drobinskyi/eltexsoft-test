<?php

namespace App\Http\Controllers;

use App\Constants;
use App\User;
use App\Http\Requests\UserRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use function Matrix\diagonal;

class UserController extends Controller
{

    /**
     * @param User $model
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('pages.users.index', ['users' => $model]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newUser()
    {
        return view('pages.users.create');
    }

    /**
     * @param User $model
     * @return mixed
     * @throws \Exception
     */
    public function data(User $model)
    {
        $crud = User::orderBy('id', 'desc')->get();
        return Datatables::of(User::all())->addColumn('action', function ($data) {
            $html = '';
            if (Auth::user()->can('edit') || Auth::user()->can('changeStatus') && Auth::user()->canChangeStatus(Auth::user(), User::find($data->id))) {
                $html .= '<button  data-source=' . url('users/' . $data->id) . ' class="btn btn-success btn-sm btn-modal" data-title="Edit Data" data-toggle="modal" data-target="#modal" data-button="Update"><i class="material-icons">edit</i></button> ';
            }
            if (Auth::user()->can('view')) {
                $html .= '<button  data-source=' . url('user/' . $data->id) . ' class="btn btn-primary btn-sm btn-modal" data-title="View Data" data-toggle="modal" data-target="#modal" data-button="OK"><i class="material-icons">visibility</i></button> ';
            }

            if (Auth::user()->hasRole('admin') && Auth::user()->id !== $data->id) {
                $html .= csrf_field();
                $html .= method_field("DELETE");
                $html .= '<button data-url="' . url('users/' . $data->id) . '" class="btn btn-delete btn-danger btn-sm"><i class="material-icons">delete_outline</i>';
            }

            return $html;
        })
            ->make(true);


    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->can('create')) {
            return view('pages.users.create');
        }
    }

    /**
     * @param Request $request
     * @return string[]
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'role' => [
                'required',
                Rule::in(Constants::ROLES)
            ],
            'status' => [
                Rule::in(Constants::STATUSES)
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'notes' => 'required|string',
            'date_of_birth' => 'required|date|date_format:Y-m-d',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'

        ])->validate();

        $token = Str::random(60);

        $user = new User;

        $user->fill($request->all());
        $user->password = Hash::make($request->get('password'));
        $user->api_token = hash('sha256', $token);
        $user->assignRole($request->get('role'));
        $save = $user->save();


        if ($save) {
            $title = "Success";
            $message = 'Saved Data Successfully';
            $type = 'success';

        } else {
            $title = "Failed";
            $message = 'Failed to Save Data';
            $type = 'danger';

        }

        $json = [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'state' => 'show'
        ];
        return $json;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = User::find($id);
        $data['role'] = $data->roles->first()->name;
        if (Auth::user()->can('view')) {
            return view('pages.users.view', compact('data'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = User::find($id);
        if (Auth::user()->can('edit') || Auth::user()->can('changeStatus')) {
            return view('pages.users.edit', compact('data'));;
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return string[]
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'role' => [
                'required',
                Rule::in(Constants::ROLES)
            ],
            'status' => [
                Rule::in(Constants::STATUSES)
            ],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|date|date_format:Y-m-d',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id, 'id')
            ],
            'notes' => 'required|string',

        ])->validate();

        $requestData = $request->all();

        $user = User::find($id);
        if (isset($requestData['status']) && !Auth::user()->canChangeStatus(Auth::user(),$user)){
            unset($requestData['status']);
        }
        $user->fill($requestData);
        $save = $user->save();

        if ($save) {
            $title = "Success";
            $message = 'Data Updated Successfully';
            $type = 'success';

        } else {
            $title = "Failed";
            $message = 'Failed to Update Data';
            $type = 'danger';

        }

        $json = [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'state' => 'show'
        ];
        return $json;


    }

    /**
     * @param $id
     * @return string[]
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user && Auth::user()->id !== $user->id) {
            $delete = $user->delete();

            if ($delete) {
                $title = "Success";
                $message = 'Deleted Data Successfully';
                $type = 'success';

            } else {
                $title = "Failed";
                $message = 'Failed to Delete Data';
                $type = 'danger';

            }


        } else {
            $title = "Failed";
            $message = 'Failed to Delete Data';
            $type = 'danger';

        }


        $json = [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'state' => 'show'
        ];
        return $json;

    }

}
