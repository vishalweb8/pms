<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Event;
use Exception;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['role_or_permission:Super Admin|event.list'])->only('index');
        $this->middleware(['role_or_permission:Super Admin|event.create'])->only(['create', 'store']);
        $this->middleware(['role_or_permission:Super Admin|event.edit'])->only(['edit', 'store']);
        $this->middleware(['role_or_permission:Super Admin|event.destroy'])->only(['destroy']);
    }

    /* Get details of all the event */
    public function index(Request $request)
    {
        view()->share('module_title', 'events');
        try{
            if($request->ajax()){
                return $this->advancefilter($request);
            }else{
                $event = $this->eventStatus($request);
            }
            $events = $event->paginate(9);
            return view('master.event.index',compact('events'));
        } catch(Exception $e){
            //
        }
    }

    /* Create event */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'event_name' => 'required|max:150',
                'event_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'description' => 'required',
                'file_url' => 'max:10240|mimes:docx,doc,pdf',
            ],[
                'file_url.max' => 'The file url must not be greater than 10MB.'
            ]);

            if($validator->fails()){
                return response()->json(array("errors" => $validator->getMessageBag()->toArray()), 422);
            }

            $event_date = Carbon::createFromFormat('d-m-Y',$request->event_date)->toDateString();
            $event = new Event();

            if ($request->hasFile('file_url')) {
                if ($event->file_url) {
                    \Storage::delete('public/upload/event/' . $event->file_url);
                }
                $file = $request->file('file_url');
                $fileName = $file->getClientOriginalName();
                $request->file_url->move(public_path('storage/upload/event/'), $fileName);
                $event->file_url = $fileName;
            }

            $event->event_name = $request->event_name;
            $event->event_date = $event_date;
            $event->start_time = date('H:i', strtotime($request->start_time));
            $event->end_time = date('H:i', strtotime($request->end_time));
            $event->description = $request->description;
            $event->save();

            return response()->json(['success'=> trans('messages.MSG_SUCCESS',['name' => 'Event'])], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /* update event */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'event_name' => 'required|max:150',
                'event_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'description' => 'required',
                'file_url' => 'max:10240|mimes:docx,doc,pdf',
            ],[
                'file_url.max' => 'The file url must not be greater than 10MB.'
            ]);

            if($validator->fails()){
                return response()->json(array("errors" => $validator->getMessageBag()->toArray()), 422);
            }

            $event_date = Carbon::createFromFormat('d-m-Y',$request->event_date)->toDateString();
            $eventFile = $request->file_url;

            if ($request->hasFile('file_url')) {
                if ($eventFile) {
                    \Storage::delete('public/upload/event/' . $eventFile);
                }
                $file = $request->file('file_url');
                $fileName = $file->getClientOriginalName();
                $request->file_url->move(public_path('storage/upload/event/'), $fileName);
                $eventFile = $fileName;
            }
            Event::where('id',$request->eventId)
                ->update([
                    'event_name' => $request->event_name,
                    'event_date' => $event_date,
                    'start_time' => date('H:i', strtotime($request->start_time)),
                    'end_time' => date('H:i', strtotime($request->end_time)),
                    'description' => $request->description,
                    'file_url' => $eventFile
                ]);
            return response()->json(['success'=> trans('messages.MSG_UPDATE',['name' => 'Event'])],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /* Delete particular event */
    public function delete(Request $request, $id)
    {
        try {
            $event = Event::find($id);
            if($event) {
                $event->delete();
                return response()->json(['success' => true, 'message' => trans('messages.MSG_DELETE',['name' => 'Event'])]);
            } else {
                return response()->json(['success' => true, 'message' => 'Event not found']);
            }
        } catch (Exception $e) {
            //
        }
    }

    /* get edit eventData */
    public function getEvent(Request $request)
    {
        if ($request->ajax()) {
            $getData = Event::where('id', $request->id)->first();
            $file = 'storage/upload/event/'.$getData->file_url;
            return response()->json(['success' => true, 'data' => $getData, 'file'=>$file]);
        }
    }

    /* get event description */
    public function getDescription(Request $request)
    {
        if($request->ajax()){
            $getDescription = Event::where('id', $request->id)->first();
            return response()->json(['success' => true, 'data' => $getDescription]);
        }
    }

    /* Event advancefilter html append data function */
    public function advancefilter(Request $request)
    {
        $html = '';
        $Array = [];
        $date = now();

        if($request->ajax()){
            if($request->all() == NULL){
                $event = Event::all();
            }else{
                $event = $this->eventStatus($request);
            }
            $event = $event->paginate(9);
            if($event){
                foreach($event as $list){
                    $html = view('master.event.partials.eventbox', compact('list'))->render();
                    array_push($Array, $html);
                }
                $paginate_html = view('master.event.partials.pagination', compact('event'))->render();
                return response()->json(['html' => $Array, 'pagination' => $paginate_html]);
            }
        }
    }

    /* check advance filter request common function */
    public function eventStatus(Request $request)
    {
        if(!empty($request->start)){
            $start  = Carbon::createFromFormat('d-m-Y', $request->start)->toDateString();
        }
        if(!empty($request->end)){
            $end  = Carbon::createFromFormat('d-m-Y', $request->end)->toDateString();
        }

        $dt = Carbon::now();
        $event = Event::select('*')->orderBy('id','desc');
        if($request->filled('search')){
            $event->where('event_name','LIKE','%'.$request->search."%");
        }
        if(!empty($start) && !empty($end)){
            $event->whereBetween('event_date',[$start, $end]);
        }
        if($request->status == 'upcoming'){
            $event->where('event_date','>=', $dt);
        }
        if($request->status == 'completed'){
            $event->where('event_date','<=', $dt);
        }
        if($request->status == 'all'){
            $event;
        }
        return $event;
    }
}
