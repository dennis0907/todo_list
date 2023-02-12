<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //將所有類別取出
        $types = Type::get();

        return response([
            'data' => $types
        ], Response::HTTP_OK); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //驗證欄位
        $this->validate($request,[
            'name' => [
                'required',
                'max:30',
                //name必須為唯一值
                Rule::unique('types','name')
            ],
            'sort' => 'nullable|integer'
        ]);
        //如果沒有給sort值,將此次寫入設為最大值
        if (!isset($request->sort)) {
            $max = Type::max('sort');
            $request['sort'] = $max+1;
        }
        
        $type = Type::create($request->all());

        return response([
            'data' => $type
        ], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return response([
            'data' => $type
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $this->validate($request, [
            'name' => [
                'max:30',
                // 確認是否有相同值 , ignore 除去本身
                Rule::unique('types', 'name')->ignore($type->name,'name')
            ],
            'sort' => 'nullable|integer'
        ]);

        $type->update($request->all());

        return response([
            'data' => $type
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
