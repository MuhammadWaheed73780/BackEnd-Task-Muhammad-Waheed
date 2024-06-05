<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;


class CategoryController extends Controller
{
    //

    public function index(Request $req, $locale)
    {
        $UserToken = session('UserToken');
        if(isset($UserToken))
        {

            if (! in_array($locale, ['en', 'ar'])) {
                abort(400);
            }
        
            App::setLocale($locale);

            // $products = Product::all();
            $cats = DB::table("Category")->select("*")->get();
            return view('ManageCategory', ['cats' => $cats, 'locale' => app()->getLocale()]);
        }
        else
        {
            return view('login', ['locale' => app()->getLocale()]);
        }
    }

    public function create(Request $req, $locale)
    {
        $UserToken = session('UserToken');
        if(isset($UserToken))
        {
            if (! in_array($locale, ['en', 'ar'])) {
                abort(400);
            }
        
            App::setLocale($locale);


            return view('AddCategory', ['locale' => app()->getLocale()]);
        }
        else
        {
            return view('login', ['locale' => app()->getLocale()]);
        }
    }

    public function update(Request $req, $locale)
    {
        $UserToken = session('UserToken');
        if(isset($UserToken))
        {
            if (! in_array($locale, ['en', 'ar'])) {
                abort(400);
            }
         
            App::setLocale($locale);
    
            return view('UpdateCategory', ['locale' => app()->getLocale()]);
        }
        else
        {
            return view('login', ['locale' => app()->getLocale()]);
        }
    }

    public function delete(Request $req, $locale)
    {
        $UserToken = session('UserToken');
        if(isset($UserToken))
        {
            if (! in_array($locale, ['en', 'ar'])) {
                abort(400);
            }
         
            App::setLocale($locale);
    
            return view('DeleteCategory', ['locale' => app()->getLocale()]);
        }
        else
        {
            return view('login', ['locale' => app()->getLocale()]);
        }
    }

    public function AddCategory(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "name" => "required|min:2|max:100",
        ]);

        if($Validator->fails())
        {
            return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
        }
        else
        {
            $Category = DB::table("Category")->where("Name", $req->name)->first();
            if($Category)
            {
                return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
            }
            else
            {
                DB::table("Category")->insert([
                    "Name" => $req->name,
                    "created_at" => Carbon::now()->setTimezone("Africa/Cairo")
                ]);

                return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
            }
        }
    }

    public function UpdateCategory(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "id" => "required",
            "name" => "nullable|min:2|max:100",
        ]);

        if ($Validator->fails()) {
            return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
            
            return Response::json(["failed" => [
                "status" => 400,
                "response" => [
                    "msg" => "Validation Error",
                    "errors" => $Validator->errors()
                ]
            ]], 400);
        }
        else
        {
            $cats = DB::table("Category")->where("id", $req->id)->first();
            if($cats)
            {
                $Name = $req->name ?? $cats->Name;

                DB::table("Category")->where("id", $req->id)->update([
                    "Name" => $Name,
                    "updated_at" => Carbon::now()->setTimezone("Africa/Cairo")
                ]);

                return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
                return Response::json([
                    "success" => [
                        "status" => 200,
                        "response" => [
                            "msg" => "Category updated successfully",
                        ]
                    ]
                ], 200);                
            }
            else
            {
                return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
            }
        }
    }

    public function DeleteCategory(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "id" => "required",
        ]);

        if ($Validator->fails()) {
            return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
        }
        else
        {
            $cats = DB::table("Category")->where("id", $req->id)->first();
            if($cats)
            {
                DB::table("Category")->where("id", $req->id)->delete();

                return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
                // return Response::json([
                //     "success" => [
                //         "status" => 200,
                //         "response" => [
                //             "msg" => "Category deleted successfully",
                //         ]
                //     ]
                // ], 200);
            }
            else
            {
                return redirect()->route('manage-category', ['locale' => app()->getLocale()]);
                // return Response::json(["failed" => [
                //     "status" => 400,
                //     "response" => [
                //         "msg" => "Product not found",
                //         "errors" => ["msg" => "Category not found"]
                //     ]
                // ]], 400);
            }
        }
    }

    public function ReadCategoryAPI(Request $req)
    {
        $Validator = Validator::make($req->all(), [
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
            ]], 400);
        }
        else
        {
            if(isset($req->id))
            {
                $Product = DB::table("Product")->where("id", $req->id)->first();
                if($Product)
                {
                    $imagePath = url('productPictures/' . $Product->Image);

                    $Cat = DB::table("Category")->where("id", $Product->CategoryID)->first();
                    $CatArr = $Cat ? ["id" => $Cat->id, "name" => $Cat->Name] : ["id" => NULL, "name" => NULL];

                    $ProductData = [
                        "id" => $Product->id,
                        "name" => $Product->Name,
                        "image" => $imagePath,
                        "category" => $CatArr,
                    ];

                    // return view('ManageProducts', compact('ProductData'));     //, 'categories'
                    return view('ManageProducts', ['products' => $products]);
                }
                else
                {
                    return Response::json(["failed" => [
                        "status" => 400,
                        "response" => [
                            "msg" => "Product not found",
                            "errors" => ["msg" => "Product not found"]
                        ]
                    ]], 400);
                }
            }
            else
            {
                $Products = DB::table("Product")->get();
                if(count($Products) > 0)
                {
                    $ProductArr = [];
                    foreach ($Products as $Product)
                    {
                        $imagePath = url('productPictures/' . $Product->Image);
                        $Cat = DB::table("Category")->where("id", $Product->CategoryID)->first();
                        $CatArr = $Cat ? ["id" => $Cat->id, "name" => $Cat->Name] : ["id" => NULL, "name" => NULL];

                        $ProductData[] = [
                            "                            id" => $Product->id,
                            "name" => $Product->Name,
                            "image" => $imagePath,
                            "category" => $CatArr,
                        ];
                    }

                    // return view('ManageProducts', compact('ProductData'));      // , 'categories'
                    return view('ManageProducts', ['products' => $products]);
                }
                else
                {
                    return Response::json(["failed" => [
                        "status" => 400,
                        "response" => [
                            "msg" => "Found no Product in DB",
                            "errors" => ["msg" => "Found no Product in DB"]
                        ]
                    ]], 400);
                }
            }
        }
    }
}
