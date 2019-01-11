<?php

namespace App\Http\Controllers;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::where(['is_active' => 1])->get();
        return view('holiday.holiday_list')->with(['holidays' => $holidays]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('holiday.create_holiday');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $color = new Holiday();
        $color->date = Carbon::parse(request('date'))->format('Y-m-d');
        $color->occasion = request('occasion');
        $color->save();
        return Redirect::back()->with('message', 'Holiday has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editholiday()
    {
        $holiday = Holiday::find(request('holiday_id'));
        return view('holiday.edit_holiday')->with(['holiday' => $holiday]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $holiday = Holiday::find(request('cid'));
        $holiday->date = Carbon::parse(request('date'))->format('Y-m-d');
        $holiday->occasion = request('occasion');
        $holiday->save();
        return Redirect::back()->with('message', 'Holiday has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $item = Holiday::find(request('holiday_id'));
        $item->is_active = 0;
        $item->save();
    }
}
