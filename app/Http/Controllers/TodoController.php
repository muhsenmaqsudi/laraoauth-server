<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $todos = Auth::user()->todos;

        return view('todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(['item' => 'required|between:2,50']);

        Auth::user()->todos()->save(new Todo($data));

        return redirect()->route('todos.index')->withStatus('Todo saved!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $data = $request->validate(['done' => 'required|boolean']);

        $todo->done = $data['done'];
        $todo->completed_on = $data['done'] == true ? Carbon::now() : null;

        return response(['status' => $todo->save() ? 'success' : 'error']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Todo $todo
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Todo $todo)
    {
        return response(['status' => $todo->delete() ? 'success' : 'error']);
    }
}
