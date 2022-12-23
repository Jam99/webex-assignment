<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Home extends Controller {

    public function index(){
        $images = Image::query()->where("public", "=", 1)->orderByDesc("created_at")->get(["path"])->toArray();

        foreach ($images as $i => $image) {
            $images[$i]["url"] = Storage::url($image["path"]);
        }

        return view("home", [
            "images" => $images,
            "common_data" => [
                "title" => "Webex Gallery"
            ]
        ]);
    }

}
