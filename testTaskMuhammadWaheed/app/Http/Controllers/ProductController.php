<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;

class ProductController extends Controller
{
    //
    public function AddProduct(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "name" => "required|min:2|max:100",
            "description" => "required|min:5|max:100",
            "category" => "required",
        ]);

        $ProductPictureDomain = "https://http://127.0.0.1:8000/public/productPictures/";
        if($req->hasFile('image'))
        {
            $imageExtension = $req->image->extension();
            $extensions = ["jpeg","PNG","png","jpg","gif"];
            if(in_array($imageExtension, $extensions))
            {
                $size = $req->image->getSize(); // in bytes
                $sizeInKB = $size / 1024;
                if($sizeInKB < 2048)
                {
                    // $imageName = $ProductPictureDomain . time().'.'.$req->image->extension();
                    $imageName = time().'.'.$req->image->getClientOriginalExtension();

                    if(!($req->image->move(public_path('productPictures'), $imageName)))
                    {
                        $imageName = 'default.jpg'; // Change 'default.jpg' to your default image filename
                    }
                }
                else
                {
                    $imageName = 'default.jpg'; // Change 'default.jpg' to your default image filename
                }
            }
            else
            {
                $imageName = 'default.jpg'; // Change 'default.jpg' to your default image filename
            }
        }
        else
        {
            $imageName = 'default.jpg'; // Change 'default.jpg' to your default image filename
        }
        
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
            // Insert New Product
            $Product = DB::table("Product")->select("*")->where("Name", $req->name)->first();
            if($Product)
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
                DB::table("Product")->insert([
                    "Name" => $req->name,
                    "Description" => $req->description,
                    "Image" => $imageName,
                    "CategoryID" => $req->category,
                    "created_at" => Carbon::now()->setTimezone("Africa/Cairo")
                ]);

                return Response::json(["success" => [
                    "status" => 200,
                    "response" => [
                                        "msg" => "Product added succesfully",
                                    ]
                        ]
                    ], 200);
            }
        }
    }

    public function UpdateProduct(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "id" => "required",
            "name" => "nullable|min:2|max:100",
            "description" => "nullable|min:5|max:100",
            "category" => "nullable",
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
            $Product = DB::table("Product")->select("*")->where("id", $req->id)->first();
            if($Product)
            {
                $ProductPictureDomain = "https://http://127.0.0.1:8000/public/productPictures/";
                if($req->hasFile('image'))
                {
                    $imageExtension = $req->image->extension();
                    $extensions = ["jpeg","PNG","png","jpg","gif"];
                    if(in_array($imageExtension, $extensions))
                    {
                        $size = $req->image->getSize(); // in bytes
                        $sizeInKB = $size / 1024;
                        if($sizeInKB < 2048)
                        {
                            // $imageName = $ProductPictureDomain . time().'.'.$req->image->extension();
                            $imageName = time().'.'.$req->image->getClientOriginalExtension();
                            $imagePath = public_path('productPictures/' . $Product->Image);
                            if (File::exists($imagePath)) {
                                File::delete($imagePath);
                            }

                            if(!($req->image->move(public_path('productPictures'), $imageName)))
                            {
                                $imageName = $Product->Image; // Change 'default.jpg' to your default image filename
                            }
                        }
                        else
                        {
                            $imageName = $Product->Image; // Change 'default.jpg' to your default image filename
                        }
                    }
                    else
                    {
                        $imageName = $Product->Image; // Change 'default.jpg' to your default image filename
                    }
                }
                else
                {
                    $imageName = $Product->Image; // Change 'default.jpg' to your default image filename
                }

                (isset($req->name)) ? $Name = $req->name : $Name = $Product->Name;
                (isset($req->description)) ? $Description = $req->description : $Description = $Product->Description;
                (isset($req->categoryId)) ? $CategoryID = $req->categoryId : $CategoryID = $Product->CategoryID;

                // Check Category ID.
                $CatID = DB::table("Category")->select("*")->where("id", $CategoryID)->first();
                if($CatID)
                {
                    DB::table("Product")->where("id", $req->id)->update([
                        "Name" => $Name,
                        "Description" => $Description,
                        "Image" => $imageName,
                        "CategoryID" => $CategoryID,
                        "updated_at" => Carbon::now()->setTimezone("Africa/Cairo")
                    ]);
    
                    return Response::json([
                                    "success" => [
                                        "status" => 200,
                                        "response" => [
                                            "msg" => "Product updated successfully",
                                        ],
                                    ],
                    ], 200);
                }
                else
                {
                    return Response::json(["failed" => [
                                    "status" => 400,
                                    "response" => [
                                        "msg" => "Given category not found",
                                        "errors" => ["msg" => "Given category not found"]
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
                                    "msg" => "Product not found",
                                    "errors" => ["msg" => "product not found"]
                                    ]
                                ]
                ], 400);
            }
        }
    }

    public function DeleteProduct(Request $req)
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
            $Product = DB::table("Product")->select("*")->where("id", $req->id)->first();
            if($Product)
            {

                // Check record images.
                if(isset($Product->Image))
                {
                    $imagePath = public_path('productPictures/' . $Product->Image);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
                DB::table("Product")->where("id", $req->id)->delete();

                return Response::json([
                                "success" => [
                                    "status" => 200,
                                    "response" => [
                                        "msg" => "Product deleted successfully",
                                    ],
                                ],
                ], 200);
            }
            else
            {
                return Response::json(["failed" => [
                                "status" => 400,
                                "response" => [
                                    "msg" => "Product not found",
                                    "errors" => ["msg" => "product not found"]
                                    ]
                                ]
                ], 400);
            }
        }
    }

    public function ReadProduct(Request $req)
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
                $Product = DB::table("Product")->select("*")->where("id", $req->id)->first();
                if($Product)
                {
                    $imagePath = public_path('productPictures/' . $Product->Image);

                    // Get Category Data.
                    $Cat = DB::table("Category")->select("*")->where("id", $Product->CategoryID)->first();
                    if($Cat)
                    {
                        $CatArr = [
                            "id" => $Cat->id,
                            "name" => $Cat->Name,
                        ];
                    }
                    else
                    {
                        $CatArr = [
                            "id" => NULL,
                            "name" => NULL,
                        ];
                    }
                    $Product = [
                        "id" => $Product->id,
                        "name" => $Product->Name,
                        "image" => $imagePath,
                        "category" => $Product->id,
                    ];

                    return Response::json([
                                    "success" => [
                                        "status" => 200,
                                        "response" => [
                                            "msg" => "Product $req->id",
                                            "data" => $Product
                                        ],
                                    ],
                    ], 200);
                }
                else
                {
                    return Response::json(["failed" => [
                                    "status" => 400,
                                    "response" => [
                                        "msg" => "Product not found",
                                        "errors" => ["msg" => "product not found"]
                                        ]
                                    ]
                    ], 400);
                }
            }
            else
            {
                $Product = DB::table("Product")->select("*")->get();
                if(sizeof($Product) > 0)
                {
                    for($i = 0; $i < sizeof($Product); $i++)
                    {
                        $imagePath = public_path('productPictures/' . $Product[$i]->Image);

                        // Get Category Data.
                        $Cat = DB::table("Category")->select("*")->where("id", $Product[$i]->CategoryID)->first();
                        if($Cat)
                        {
                            $CatArr = [
                                "id" => $Cat->id,
                                "name" => $Cat->Name,
                            ];
                        }
                        else
                        {
                            $CatArr = [
                                "id" => NULL,
                                "name" => NULL,
                            ];
                        }
                        $ProductArr[$i] = [
                            "id" => $Product[$i]->id,
                            "name" => $Product[$i]->Name,
                            "image" => $imagePath,
                            "category" => $CatArr,
                        ];
                    }
                    

                    return Response::json([
                                    "success" => [
                                        "status" => 200,
                                        "response" => [
                                            "msg" => "Products",
                                            "data" => $ProductArr
                                        ],
                                    ],
                    ], 200);
                }
                else
                {
                    return Response::json(["failed" => [
                                    "status" => 400,
                                    "response" => [
                                        "msg" => "Found no Product in DB",
                                        "errors" => ["msg" => "Found no Product in DB"]
                                        ]
                                    ]
                    ], 400);
                }
            }
        }
    }

    public function FilterProduct(Request $req)
    {
        $Validator = Validator::make(($req->all()), 
        [
            "filter" => "nullable",
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
            $Filter = $req->filter;
            $Product = DB::table('Category')
                ->join('Product', 'Category.id', '=', 'Product.CategoryID')
                ->where('Product.name', 'like', "%%$Filter%%")
                ->orWhere('Category.name', 'like', "%%$Filter%%") // Search in both tables
                ->get();

            // $Product = DB::table("Product")->select("*")->get();
            if(sizeof($Product) > 0)
            {
                for($i = 0; $i < sizeof($Product); $i++)
                {
                    $imagePath = public_path('productPictures/' . $Product[$i]->Image);

                    // Get Category Data.
                    $Cat = DB::table("Category")->select("*")->where("id", $Product[$i]->CategoryID)->first();
                    if($Cat)
                    {
                        $CatArr = [
                            "id" => $Cat->id,
                            "name" => $Cat->Name,
                        ];
                    }
                    else
                    {
                        $CatArr = [
                            "id" => NULL,
                            "name" => NULL,
                        ];
                    }
                    $ProductArr[$i] = [
                        "id" => $Product[$i]->id,
                        "name" => $Product[$i]->Name,
                        "image" => $imagePath,
                        "category" => $CatArr,
                    ];
                }
                

                return Response::json([
                                "success" => [
                                    "status" => 200,
                                    "response" => [
                                        "msg" => "Products",
                                        "data" => $ProductArr
                                    ],
                                ],
                ], 200);
            }
            else
            {
                return Response::json(["failed" => [
                                "status" => 400,
                                "response" => [
                                    "msg" => "Found no Product in DB",
                                    "errors" => ["msg" => "Found no Product in DB"]
                                    ]
                                ]
                ], 400);
            }
            
        }
    }
}
