<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\Type;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //取得網址
        $url = $request->url();
        //取得參數
        $queryParams = $request->query();
        //將傳入參數固定排序
        ksort($queryParams);
        //將參數字串化
        $queryString = http_build_query($queryParams);
        //完整網址 ps:含參數
        $fullUrl = "{$url}?{$queryString}";

        //確認快取內是否有紀錄 如果有直接返回快取內資料
        if (Cache::has($fullUrl)) {
            return Cache::get($fullUrl);
        }
        //未提供資料數量則給10筆
        $limit = $request->limit ?? 10;

        $query = Todo::query();
        //篩選欄位
        // if (isset($request->filters)) {
        //     $filters = explode(',', $request->filters);
        //     foreach ($filters as $key => $filter) {
        //         list($key, $value) = explode(':', $filter);
        //         $query->where($key, 'like', "%$value%");
        //     }
        // }
        //篩選日期
        if (isset($request->date)) {
            $date = $request->date;
            if ($date == 'today') {
                $query->where('todo_time', date("Y-m-d"));
            } else {
                $query->where('todo_time', $date);
            }
        }
        //排列方式
        if (isset($request->sorts)) {
            $sorts = explode(',', $request->sorts);
            foreach ($sorts as $key => $sort) {
                list($key, $value) = explode(':', $sort);
                if ($value == 'asc' || $value == 'desc') {
                    $query->orderBy($key, $value);
                }
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $todo = $query->paginate($limit)->appends($request->query());
        //返回todo 前將 fullUrl存入快取60s
        return Cache::remember($fullUrl, 60, function () use ($todo) {
            return response($todo, Response::HTTP_OK);
        });
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
        $this->validate($request, [
            'title' => 'required|string|max:10',
            'content' => 'nullable|string|max:255',
            'finished' => 'nullable|boolean',
            'todo_time' => 'required|date'
        ]);

        $request['user_id'] = rand(1, 5);
        $todo = Todo::create($request->all());
        $todo = $todo->refresh();
        return response($todo, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return new TodoResource($todo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $this->validate($request, [
            'title' => 'nullable|string|max:10',
            'content' => 'nullable|string|max:255',
            'finished' => 'nullable|boolean',
            'todo_time' => 'nullable|date'
        ]);
        // $request['user_id'] = rand(1, 5);
        $todo->update($request->all());
        return response($todo, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
