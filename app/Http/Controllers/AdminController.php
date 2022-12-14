<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

//Models


class AdminController extends Controller
{
    public function redirect(){
        $usertype = Auth::user() -> usertype;

        if($usertype == '1'){
            $data = Product::paginate(6);
            return view('admin.Home', compact('data'));
        }
        else{
            $data = Product::paginate(6);
            return view('User.UserHome',compact('data'));
        }
    }

    public function product(){
        return view('Admin.Product');
    }

    //Upload a new product
    public function UploadProduct(Request $request){
        $data = new Product;
        $image = $request->file;
        $imageName = time().'.'.$image -> getClientOriginalExtension();
        $request -> file -> move('productImage', $imageName);

        $data->image = $imageName;
        $data->title = $request->title;
        $data->price = $request->price;
        $data->description = $request->description;
        $data->quantity = $request->quantity;

        $data->save();

        return redirect()->back()->with('message', 'Product added successfully');
    }

    public function DeleteProduct($id){
        $data = Product::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Product deleted successfully');
    }

    public function updateview($id){
        $data = Product::find($id);

        return view('Admin.UpdateView', compact('data'));
    }

    public function UpdateProduct(Request $request, $id){
        $data = Product::find($id);

        $image = $request->file;

        if($image){
            $imageName = time().'.'.$image -> getClientOriginalExtension();
            $request -> file -> move('productImage', $imageName);
            $data->image = $imageName;
        }
        
        $data->title = $request->title;
        $data->price = $request->price;
        $data->description = $request->description;
        $data->quantity = $request->quantity;

        $data->save();

        return redirect()->back()->with('message', 'Product is successfully updated');
    }

    public function ExportProducts(){
       return Excel::download(new ProductsExport, 'Products.xlsx');
    }
}
