<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

use Session;

class GameController extends Controller
{
    public function createGame(Request $request){

        $request->validate([
            'name' => 'required',
            'thumbnail' => 'required',
            'url' => 'required',
            'status' => 'required'
        ]);

        if ($request->file('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/banners/', $filename);
            $bannerPath = 'images/banners/'.$filename;
        }

        $request['banner'] = $bannerPath;

        Game::create($request->all());
        $request->session()->flash('success', 'Game has been created successfully!');
        return back();
    }

    public function updateGame($id, Request $request){

        if ($request->file('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move('images/banners/', $filename);
            $bannerPath = 'images/banners/'.$filename;
            $request['banner'] = $bannerPath;
        }

        Game::find($id)->update($request->all());
        $request->session()->flash('success', 'Game has been created successfully!');
        return back();
    }

    public function deleteGame($id){
        if(Game::find($id)->delete()){
            Session::flash('success', 'Game has been deleted successfully!');
        return back();
        }
    }
}
