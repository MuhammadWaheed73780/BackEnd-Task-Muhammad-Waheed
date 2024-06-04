<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;


class CategoryController extends Controller
{
    //
    public function AddCat(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "name" => "required|min:2|max:100",
        ]);
        
        if($Validator->fails())
        {
            return Response::json(["failed" => [
                            "status" => 400,
                            "response" => [
                                "msg" => "Validation Error",
                                "errors" => $Validator->errors()
                                ]
                            ]
            ], 400);
        }
        else
        {
            // Insert New Category
            $Cats = DB::table("Category")->select("*")->where("Name", $req->name)->first();
            if($Cats)
            {
                return Response::json(["failed" => [
                                "status" => 400,
                                "response" => [
                                    "msg" => "Duplicate data",
                                    "errors" => ["msg" => "Duplicate data"]
                                    ]
                                ]
                ], 400);
            }
            else
            {
                DB::table("Category")->insert(["Name" => $req->name, "created_at" => Carbon::now()->setTimezone("Africa/Cairo")]);

                return Response::json(["success" => [
                    "status" => 200,
                    "response" => [
                                        "msg" => "Category added succesfully",
                                    ]
                        ]
                    ], 200);
            }
        }
    }

    public function UpdateCat(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "name" => "required",
            "updateName" => "nullable",
        ]);
        
        if($Validator->fails())
        {
            return Response::json(["failed" => [
                            "status" => 400,
                            "response" => [
                                "msg" => "Validation Error",
                                "errors" => $Validator->errors()
                                ]
                            ]
            ], 400);
        }
        else
        {
            $Cats = DB::table("Category")->select("*")->where("Name", $req->name)->first();
            if($Cats)
            {
                $Cats1 = DB::table("Category")->select("*")->where("Name", $req->updateName)->get();

                if(isset($req->updateName) && $req->updateName !== $Cats->Name && sizeof($Cats1) <= 0)
                {

                    DB::table("Category")->where("Name", $req->name)->update(["Name" => $req->updateName, "updated_at" => Carbon::now()->setTimezone("Africa/Cairo")]);

                    return Response::json(["success" => [
                        "status" => 200,
                        "response" => [
                                            "msg" => "Category updated succesfully",
                                        ]
                            ]
                        ], 200);
                }
                else
                {
                    return Response::json(["failed" => [
                                    "status" => 400,
                                    "response" => [
                                        "msg" => "Category not Updated",
                                        "errors" => ["msg" => "Category not Updated"]
                                        ]
                                    ]
                    ], 400);
                }
            }
            else
            {
                return Response::json(["failed" => [
                                "status" => 400,
                                "response" => [
                                    "msg" => "Category not found",
                                    "errors" => ["msg" => "Category not found"]
                                    ]
                                ]
                ], 400);
            }
        }
    }

    public function DeleteCat(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "id" => "required",
        ]);
        
        if($Validator->fails())
        {
            return Response::json(["failed" => [
                            "status" => 400,
                            "response" => [
                                "msg" => "Validation Error",
                                "errors" => $Validator->errors()
                                ]
                            ]
            ], 400);
        }
        else
        {
            $Cats = DB::table("Category")->select("*")->where("id", $req->id)->first();
            if($Cats)
            {
                DB::table("Category")->where('id', $req->id)->delete();

                return Response::json(["success" => [
                    "status" => 200,
                    "response" => [
                                        "msg" => "Category deleted succesfully",
                                    ]
                        ]
                    ], 200);
            }
            else
            {
                return Response::json(["failed" => [
                                "status" => 400,
                                "response" => [
                                    "msg" => "Category not found",
                                    "errors" => ["msg" => "Category not found"]
                                    ]
                                ]
                ], 400);
            }
        }
    }

    public function ReadCat(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "id" => "nullable",
        ]);
        
        if($Validator->fails())
        {
            return Response::json(["failed" => [
                            "status" => 400,
                            "response" => [
                                "msg" => "Validation Error",
                                "errors" => $Validator->errors()
                                ]
                            ]
            ], 400);
        }
        else
        {
            if(isset($req->id))
            {
                $Cats = DB::table("Category")->select("*")->where("id", $req->id)->first();
                if($Cats)
                {
                    $Returned = [
                        "id" => $Cats->id,
                        "name" => $Cats->Name,
                        "created_at" => $Cats->created_at,
                        "updated_at" => $Cats->updated_at,
                    ];
                    return Response::json(["success" => [
                        "status" => 200,
                        "response" => [
                                            "msg" => "Category $req->id",
                                            "data" => $Returned
                                        ]
                            ]
                        ], 200);
                }
                else
                {
                    return Response::json(["failed" => [
                                    "status" => 400,
                                    "response" => [
                                        "msg" => "Category not found",
                                        "errors" => ["msg" => "Category not found"]
                                        ]
                                    ]
                    ], 400);
                }
            }
            else
            {
                $Cats = DB::table("Category")->select("*")->get();
                if(sizeof($Cats) > 0)
                {
                    $Returned = [];
                    for($i = 0; $i < sizeof($Cats); $i++)
                    {
                        $Returned[$i] = [
                            "id" => $Cats[$i]->id,
                            "name" => $Cats[$i]->Name,
                            "created_at" => $Cats[$i]->created_at,
                            "updated_at" => $Cats[$i]->updated_at,
                        ];
                    }
                    return Response::json(["success" => [
                        "status" => 200,
                        "response" => [
                                            "msg" => "Categories",
                                            "data" => $Returned
                                        ]
                            ]
                        ], 200);
                }
                else
                {
                    return Response::json(["failed" => [
                                    "status" => 400,
                                    "response" => [
                                        "msg" => "No categories in DB",
                                        "errors" => ["msg" => "No categories in DB"]
                                        ]
                                    ]
                    ], 400);
                }
            }
        }
    }
}
