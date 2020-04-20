<?php

namespace App\Http\Controllers;

use App\Constants;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ApiController extends Controller
{

    private $token;
    private $apiUser;

    public function __construct(Request $request)
    {
        $this->token = $request->header('api_token');
        $this->apiUser = User::where(['api_token' => $this->token])->first();

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = [
            'success' => false
        ];

        try {
            if ($this->apiUser->can('view')) {
                $users = User::all();
                $response['data'] = $users;
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'No permission for that';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $response = [
            'success' => false
        ];

        $content = json_decode($request->getContent(), true);
        try {
            if ($this->apiUser->can('create')) {
                if ($content) {
                    $validator = Validator::make($content, [
                        'role' => [
                            'required',
                            Rule::in(Constants::ROLES)
                        ],
                        'status' => [
                            Rule::in(Constants::STATUSES)
                        ],
                        'first_name' => 'required|string',
                        'last_name' => 'required|string',
                        'email' => [
                            'required',
                            'email',
                            Rule::unique('users', 'email')
                        ],
                        'date_of_birth' => 'required|date|date_format:Y-m-d',
                        'notes' => 'required|string',
                        'password' => 'required|min:6',
                    ]);
                    if ($validator->passes()) {
                        $token = Str::random(60);
                        $user = new User();
                        $user->fill($content);
                        $user->api_token = hash('sha256', $token);
                        $user->password = bcrypt($content['password']);
                        $user->assignRole($content['role']);
                        $user->save();
                        $response['message'] = 'User was successfully created';
                        $response['success'] = true;
                        $response['data'] = $user->attributesToArray();
                    } else {
                        $response['message'] = 'Validation fails';
                        $response['errors'] = $validator->errors();
                    }
                }
            } else {
                $response['message'] = 'No permission for that';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id)
    {
        $response = [
            'success' => false
        ];

        try {
            if ($this->apiUser->can('view')) {
                $user = User::find($id);
                $response['data'] = $user;
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'No permission for that';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $response = [
            'success' => false
        ];

        $content = json_decode($request->getContent(), true);
        try {
            if ($content) {
                $validator = Validator::make($content, [
                    'role' => [
                        'nullable',
                        Rule::in(Constants::ROLES)
                    ],
                    'status' => [
                        Rule::in(Constants::STATUSES)
                    ],
                    'first_name' => 'nullable|string',
                    'last_name' => 'nullable|string',
                    'email' => [
                        'nullable',
                        'email',
                        Rule::unique('users', 'email')->ignore($id, 'id')
                    ],
                    'date_of_birth' => 'nullable|date|date_format:Y-m-d',
                    'notes' => 'nullable|string',
                    'password' => 'nullable|min:6',
                ]);
                if ($validator->passes()) {
                    $user = User::find($id);
                    if ($user) {

                        if (!$this->apiUser->canChangeStatus($this->apiUser, $user) && isset($content['status'])){
                            unset($content['status']);
                        }

                        if ($this->apiUser->can('edit')) {

                            $user->fill($content);
                            if (isset($content['password'])) {
                               $user->password = bcrypt($content['password']);
                            }
                            if (isset($content['role'])) {
                                $user->assignRole($content['role']);
                            }
                        } elseif ($this->apiUser->can('changeStatus')) {
                            if (isset($content['status'])) {
                                $user->status = $content['status'];
                            }
                        }

                        $user->save();

                        $response['message'] = 'User was successfully updated';
                        $response['success'] = true;
                        $response['data'] = $user->attributesToArray();
                    } else {
                        $response['message'] = 'User not found';
                    }
                } else {
                    $response['message'] = 'Validation fails';
                    $response['errors'] = $validator->errors();
                }

            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $response = [
            'success' => false
        ];

        try {
            if ($this->apiUser->can('delete')) {
                $user = User::find($id);
                if ($user && $this->apiUser->id !== $user->id){
                    $delete = $user->delete();
                    if ($delete) {
                        $response['success'] = true;
                        $response['message'] = 'User successfully deleted';
                    } else {
                        $response['message'] = 'User has not been deleted';
                    }
                } else{
                    $response['message'] = 'Cannot delete self';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'No permission for that';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

}
