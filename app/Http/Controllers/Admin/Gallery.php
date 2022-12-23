<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class Gallery extends Controller
{
    public function index(){
        if(session()->get("_image_upload_success")){
            $image_upload_count = session()->get("_image_upload_count");
        }

        $images = Image::query()->orderByDesc("created_at")->get(["id", "path", "public"])->toArray();

        foreach ($images as $i => $image) {
            $images[$i]["url"] = Storage::url($image["path"]);
        }

        return view("admin/gallery", [
            "image_upload_count" => isset($image_upload_count) ? $image_upload_count : null,
            "images" => $images,
            "common_data" => [
                "title" => "Admin - Gallery"
            ]
        ]);
    }

    public function upload(){
        return view("admin/upload", [
            "common_data" => [
                "title" => "Admin - Upload images"
            ]
        ]);
    }

    public function ajaxUpload(){
        try{
            //if there is no input file
            if(!\Illuminate\Support\Facades\Request::hasFile("images")) {
                throw new \Exception();
            }

            $files = \Illuminate\Support\Facades\Request::allFiles();

            Validator::validate($files, [
                "images" => [
                    "required"
                ],
                "images.*" => [
                    "mimes:png,jpg,jpeg,webp",
                    File::image()->max(2048)
                    ->dimensions(Rule::dimensions()->maxWidth(3000)->maxHeight(3000))
                ]
            ]);

            $success_count = 0;

            foreach($files["images"] as $file){
                $path = $file->store("public/images");

                if(!$path)
                    continue;

                $image = new Image();
                $image->path = $path;
                $image->public = 1;
                $image->save();

                $success_count++;
            }

            \Illuminate\Support\Facades\Request::session()->flash("_image_upload_success", 1);
            \Illuminate\Support\Facades\Request::session()->flash("_image_upload_count", $success_count);

            return ["success" => true];
        }
        catch(\Exception $e){
            return ["success" => false, "msg" => $e->getMessage()];
        }
    }

    public function ajaxToggleImage(){
        $id = (int)\Illuminate\Support\Facades\Request::post("id");
        $success = false;

        if($id){
            $image = Image::find($id);
            $image->public = (int)!$image->public;
            $success = $image->save();
        }

        return ["success" => $success];
    }

    public function ajaxDeleteImage(){
        $id = (int)\Illuminate\Support\Facades\Request::post("id");
        $success = false;

        if($id){
            $image = Image::find($id);
            $success = $image->delete();

            if($success){ //also delete from disk
                Storage::delete($image->path);
            }
        }

        return ["success" => $success];
    }
}
