<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); //对可以删除和创建微博两个动作进行权限的过滤。
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }

    public function destroy(Status $status) //Laravel自动查找并注入对应ID的实例对象 \$status 找不到就抛异常
    {
        $this->authorize('destroy', $status);//做删除授权的检测，不通过会抛出403异常 
        $status->delete();//调用Eloquent模型的 delete方法对微博进行删除
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}