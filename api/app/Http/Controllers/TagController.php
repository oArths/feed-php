<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Models\Tag;

class TagController extends Controller
{

    public function create_tag(TagRequest $parms){
        $insert = [
            'name' => $parms->tag,
        ];
        $tag = Tag::create($insert);

        return jsonResponse('Tag criada com sucesso', 201);
    }
    public function get_tags(){

        $allTags = Tag::pluck('name');
     

        return jsonResponse('todas as tags', 200, $allTags);
    }

}
