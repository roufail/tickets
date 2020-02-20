<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\TicketRequest;
use App\Ticket;
use App\User;
use App\Mail\TicketAssigned;
use App\Mail\TicketStatusChanged;



class TicketsController extends Controller
{
    private $admins;

    public function __construct() {
        $this->admins  = User::pluck('name','id')->toArray();

        $this->middleware('can:Create Ticket', ['only' => ['create']]);
        $this->middleware('can:Edit Ticket', ['only' => ['edit']]);
        $this->middleware('can:Delete Ticket', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users   = User::pluck('name','id')->prepend('','');
        if(request()->exists('from_date')){
            $tickets = Ticket::where(function($query) {
                if(request()->from_date != '' && request()->to_date != '')
                {
                    $query->whereBetween('deadline',[request()->from_date,request()->to_date]);
                }
                if(request()->user != '') {
                    $query->where('user_id',request()->user);
                }
                if(request()->assign != '') {
                    $query->where('assign_id',request()->assign);
                }
            });
        } else {
            $tickets = Ticket::where('user_id',auth()->user()->id)->orwhere('assign_id',auth()->user()->id);
        }


        $tickets = $tickets->with(['user','assign'])->orderBy('id','desc')->get();
        return view('admin.tickets.list',compact('tickets','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ticket = new Ticket;
        $admins = $this->admins;
        return view('admin.tickets.form',compact('ticket','admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        //
        $ticket = auth()->user()->tickets()->create($request->all());
        if(!$ticket){
            return redirect()->back()->withErrors(['error' => __('tickets.messages.ticket_saved_error')]);
        }
        $this->sendemail($ticket,'assigned');
        return redirect()->route('admin.tickets.index')->with(['success' => __('tickets.messages.ticket_saved_success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return view('admin.tickets.form',compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {

        // $this->check_access($ticket);
        $admins = $this->admins;
        return view('admin.tickets.form',compact('ticket','admins'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request,Ticket $ticket)
    {
        // $this->check_access($ticket);
        if(!$ticket->update($request->all())){
            return redirect()->back()->withErrors(['error' => __('tickets.messages.ticket_updated_error')]);
        }
        return redirect()->route('admin.tickets.index')->with(['success' => __('tickets.messages.ticket_updated_success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        // $this->check_access($ticket);
        if(!$ticket->delete()){
            return redirect()->back()->withErrors(['error' => __('tickets.messages.ticket_deleted_error')]);
        }
        return redirect()->route('admin.tickets.index')->with(['success' => __('tickets.messages.ticket_deleted_success')]);

    }

    public function changeStatus($id)
    {
        $ticket = Ticket::findOrFail($id);
        // 1 closed 0 opened
        if(($ticket->status == 1 && !auth()->user()->can('Open Ticket')) || ($ticket->status == 0 && !auth()->user()->can('Close Ticket'))) {
            return abort(403,'User does not have the right permissions.');
        }

        if(!$ticket->update(['status' => !$ticket->status])){
            return redirect()->back()->withErrors(['error' => __('tickets.messages.ticket_change_status_error')]);
        }
        $this->sendemail($ticket,'changed');

        return redirect()->route('admin.tickets.index')->with(['success' => __('tickets.messages.ticket_change_status_sucess',['status' => $ticket->status ? __('closed') : __('opened')])]);
    }

    public function check_access($ticket) {
        if(auth()->user()->id != $ticket->user_id) {
            return abort(404);
        }
    }

    public function sendemail($ticket,$message_type) {
        if($message_type == 'assigned')
        {
            Mail::to($ticket->assign->email)->send(new TicketAssigned($ticket));
        }
        if($message_type == 'changed')
        {
            if(auth()->user()->id != $ticket->user_id) {
                Mail::to($ticket->user->email)->send(new TicketStatusChanged($ticket));
            }elseif(auth()->user()->id != $ticket->assign_id) {
                Mail::to($ticket->assign->email)->send(new TicketStatusChanged($ticket));
            }
        }
    }
}
