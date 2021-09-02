<?php

namespace App\Http\Controllers\MailMerge;

use App\Models\MailMerge\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Recipient::class, 'recipient');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipients = Recipient::all()->where('link', '=', '');
        return view('apps.mailmerge.recipient.index')->with('recipients', $recipients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.mailmerge.recipient.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:recipient',
            'code' => 'required',
        ]);

        $recipient = new Recipient([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $recipient->save();
        return redirect(route('apps.mailmerge.recipient.index'))->with('status', __('Recipient !'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailMerge\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function show(Recipient $recipient)
    {
        return redirect(route('apps.mailmerge.recipient.index'));
        //return view('apps.mailmerge.recipient.show', compact('recipient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailMerge\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipient $recipient)
    {
        return view('apps.mailmerge.recipient.edit', compact('recipient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailMerge\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipient $recipient)
    {
        $request->validate(
            [
                'name'=>'required',
                'code' => 'required'
            ]
        );

        $recipient->name = $request->get('name');
        $recipient->code = $request->get('code');
        $recipient->updated_by = Auth::user()->id;
        $recipient->save();

        // Check if we need to delete aliases
        //dd($request->del_aliases);
        $delObj = json_decode($request->del_aliases);
        foreach ($delObj as $id) {
            $link = Recipient::find($id);

            // Just a sanity check
            if ($link->link == $recipient->name) {
                $link->delete();
            }
        }

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', __('Recipient updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailMerge\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipient $recipient)
    {
        $recipient->delete();

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', __('Recipient deleted!'));
    }

    /**
     * Return a listing of the resource in json.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $this->authorize('viewAny', Recipient::class);
        $recipients = Recipient::all(['name','code']);
        return response()->json($recipients);
    }

    /**
     * Store a newly created list of resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMany(Request $request)
    {
        if ($request->user()->cannot('create', Recipient::class)) {
            abort(403);
        }

        $request->validate(
            [
                'many.*.name'=>'unique:recipients,name|required|string',
                'many.*.code' => 'required|string',
                'many.*.link' => 'required|string',
            ]
        );

        if (!$request->has("many")) {
            return response('Wrong post', 500);
        }

        $request->whenHas(
            'many',
            function ($input) {
                $recipients = collect();

                foreach ($input as $item) {
                    $recipients->push(
                        Recipient::make(
                            [
                                'name' => $item['name'],
                                'code' => $item['code'],
                                'link' => $item['link'],
                                'updated_by' => Auth::user()->id,
                                'created_by' => Auth::user()->id,
                            ]
                        )
                    );
                }

                DB::table('recipients')->insert($recipients->toArray());
            }
        );

        return response('', 200);
    }
}
