<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;

class ProductController extends Controller
{
    public function index(Request $req, $locale)
    {
        $UserToken = session('UserToken');
        if(isset($UserToken))
        {
            if (! in_array($locale, ['en', 'ar'])) {
                abort(400);
            }
         
            App::setLocale($locale);
    
            $products = DB::table('Category')
            ->join('Product', 'Category.id', '=', 'Product.CategoryID')
            ->select('Product.*', 'Category.Name as categoryName', 'Category.id as categoryId')
            ->get();
    
            return view('ManageProducts', ['products' => $products, "locale" => app()->getLocale()]);
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
    
    
            return view('AddProduct', ['locale' => app()->getLocale()]);
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
    
            return view('UpdateProduct', ['locale' => app()->getLocale()]);
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
    
            return view('DeleteProduct', ['locale' => app()->getLocale()]);
        }
        else
        {
            return view('login', ['locale' => app()->getLocale()]);
        }
    }

    public function filter(Request $req, $locale)
    {
        $UserToken = session('UserToken');
        if(isset($UserToken))
        {
            if (! in_array($locale, ['en', 'ar'])) {
                abort(400);
            }
         
            App::setLocale($locale);
    
            return view('FilterProduct', ['locale' => app()->getLocale()]);
        }
        else
        {
            return view('login', ['locale' => app()->getLocale()]);
        }
    }

    public function AddProduct(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "name" => "required|min:2|max:100",
            "description" => "required|min:5|max:100",
            "category" => "required",
        ]);

        $ProductPictureDomain = "http://127.0.0.1:8000/public/productPictures/";
        $imageName = 'default.jpg';

        if($req->hasFile('image'))
        {
            $imageExtension = $req->image->extension();
            $extensions = ["jpeg", "PNG", "png", "jpg", "gif"];
            if(in_array($imageExtension, $extensions))
            {
                $sizeInKB = $req->image->getSize() / 1024;
                if($sizeInKB < 2048)
                {
                    $imageName = time() . '.' . $req->image->getClientOriginalExtension();
                    $req->image->move(public_path('productPictures'), $imageName);
                }
            }
        }

        if($Validator->fails())
        {
            return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
            // return Response::json(["failed" => [
            //     "status" => 400,
            //     "response" => [
            //         "msg" => "Validation Error",
            //         "errors" => $Validator->errors()
            //     ]
            // ]], 400);
        }
        else
        {
            $Product = DB::table("Product")->where("Name", $req->name)->first();
            if($Product)
            {
                return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                // return Response::json(["failed" => [
                //     "status" => 400,
                //     "response" => [
                //         "msg" => "Duplicate data",
                //         "errors" => ["msg" => "Duplicate data"]
                //     ]
                // ]], 400);
            }
            else
            {
                DB::table("Product")->insert([
                    "Name" => $req->name,
                    "Description" => $req->description,
                    "Image" => $imageName,
                    "CategoryID" => $req->category,
                    "created_at" => Carbon::now()->setTimezone("Africa/Cairo")
                ]);

                return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                // return Response::json(["success" => [
                //     "status" => 200,
                //     "response" => [
                //         "msg" => "Product added successfully",
                //     ]
                // ]], 200);
            }
        }
    }

    public function UpdateProduct(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "id" => "required",
            "name" => "nullable|min:2|max:100",
            "description" => "nullable|min:5|max:100",
            "category" => "nullable",
        ]);

        if ($Validator->fails()) {
            return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
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
            $Product = DB::table("Product")->where("id", $req->id)->first();
            if($Product)
            {
                $imageName = $Product->Image;
                if($req->hasFile('image'))
                {
                    $imageExtension = $req->image->extension();
                    $extensions = ["jpeg", "PNG", "png", "jpg", "gif"];
                    if(in_array($imageExtension, $extensions))
                    {
                        $sizeInKB = $req->image->getSize() / 1024;
                        if($sizeInKB < 2048)
                        {
                            $imageName = time() . '.' . $req->image->getClientOriginalExtension();
                            $imagePath = public_path('productPictures/' . $Product->Image);
                            if($Product->Image !== "default.jpg")
                            {
                                if(File::exists($imagePath))
                                {
                                    File::delete($imagePath);
                                }
                            }
                            $req->image->move(public_path('productPictures'), $imageName);
                        }
                    }
                }

                $Name = $req->name ?? $Product->Name;
                $Description = $req->description ?? $Product->Description;
                $CategoryID = $req->category ?? $Product->CategoryID;

                $CatID = DB::table("Category")->where("id", $CategoryID)->first();
                if($CatID)
                {
                    DB::table("Product")->where("id", $req->id)->update([
                        "Name" => $Name,
                        "Description" => $Description,
                        "Image" => $imageName,
                        "CategoryID" => $CategoryID,
                        "updated_at" => Carbon::now()->setTimezone("Africa/Cairo")
                    ]);

                    return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                    return Response::json([
                        "success" => [
                            "status" => 200,
                            "response" => [
                                "msg" => "Product updated successfully",
                            ]
                        ]
                    ], 200);
                }else
                {
                    return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                    // return Response::json(["failed" => [
                    //     "status" => 400,
                    //     "response" => [
                    //         "msg" => "Given category not found",
                    //         "errors" => ["msg" => "Given category not found"]
                    //     ]
                    // ]], 400);
                }
            }
            else
            {
                return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                // return Response::json(["failed" => [
                //     "status" => 400,
                //     "response" => [
                //         "msg" => "Product not found",
                //         "errors" => ["msg" => "Product not found"]
                //     ]
                // ]], 400);
            }
        }
    }

    public function DeleteProduct(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "id" => "required",
        ]);

        if ($Validator->fails()) {
            return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
            // return Response::json(["failed" => [
            //     "status" => 400,
            //     "response" => [
            //         "msg" => "Validation Error",
            //         "errors" => $Validator->errors()
            //     ]
            // ]], 400);
        }
        else
        {
            $Product = DB::table("Product")->where("id", $req->id)->first();
            if($Product)
            {
                if(isset($Product->Image))
                {
                    if($Product->Image !== "default.jpg")
                    {
                        $imagePath = public_path('productPictures/' . $Product->Image);
                        if(File::exists($imagePath))
                        {
                            File::delete($imagePath);
                        }
                    }
                }
                DB::table("Product")->where("id", $req->id)->delete();

                return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                return Response::json([
                    "success" => [
                        "status" => 200,
                        "response" => [
                            "msg" => "Product deleted successfully",
                        ]
                    ]
                ], 200);
            }
            else
            {
                return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                // return Response::json(["failed" => [
                //     "status" => 400,
                //     "response" => [
                //         "msg" => "Product not found",
                //         "errors" => ["msg" => "Product not found"]
                //     ]
                // ]], 400);
            }
        }
    }

    public function ReadProduct(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "id" => "nullable",
        ]);

        if ($Validator->fails()) {
            return Response::json(["failed" => [
                "status" => 400,
                "response" => [
                    "msg" => "Validation Error",
                    "errors" => $Validator->errors()
                ]
            ]], 400);
        } else {
            if (isset($req->id)) {
                $Product = DB::table("Product")->where("id", $req->id)->first();
                if ($Product) {
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
                } else {
                    return Response::json(["failed" => [
                        "status" => 400,
                        "response" => [
                            "msg" => "Product not found",
                            "errors" => ["msg" => "Product not found"]
                        ]
                    ]], 400);
                }
            } else {
                $Products = DB::table("Product")->get();
                if (count($Products) > 0) {
                    $ProductArr = [];
                    foreach ($Products as $Product) {
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
                } else {
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

    public function FilterProduct(Request $req)
    {
        $Validator = Validator::make($req->all(), [
            "filter" => "nullable",
        ]);

        if($Validator->fails())
        {
            return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
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
            $Filter = $req->filter;
            $products = DB::table('Category')
                ->join('Product', 'Category.id', '=', 'Product.CategoryID')
                ->select('Product.*', 'Category.Name as categoryName', 'Product.Name as productName ')
                ->where('Product.name', 'like', "%%$Filter%%")
                ->orWhere('Category.name', 'like', "%%$Filter%%")
                ->get();

            
            if(count($products) > 0)
            {
                $ProductArr = [];
                for($i = 0; $i < sizeof($products); $i++)
                {
                    $Cat = DB::table("Category")->where("id", $products[$i]->CategoryID)->first();
                    $CatArr = $Cat ? ["id" => $Cat->id, "name" => $Cat->Name] : ["id" => NULL, "name" => NULL];
                }

                return view('ManageProducts', ['products' => $products, "locale" => app()->getLocale()]);
            }
            else
            {
                return redirect()->route('manage-products' , ["locale" => app()->getLocale()]);
                
                // return Response::json(["failed" => [
                //     "status" => 400,
                //     "response" => [
                //         "msg" => "Found no Product in DB",
                //         "errors" => ["msg" => "Found no Product in DB"]
                //     ]
                // ]], 400);
            }
        }
    }
}

?>