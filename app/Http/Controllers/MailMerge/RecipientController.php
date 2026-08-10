<?php

namespace App\Http\Controllers\MailMerge;

use App\Http\Controllers\Controller;
use App\Models\MailMerge\Recipient;
use App\Services\StringConverter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
     * @return Response
     */
    public function index()
    {
        $recipients = Recipient::with('creator')->where('link', '=', '')->get();

        return view('apps.mailmerge.recipient.index')->with('recipients', $recipients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('apps.mailmerge.recipient.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255|required|unique:recipients',
            'code' => 'string|max:255|required',
        ]);

        $recipient = new Recipient([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $recipient->save();

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', __('Recipient created!'));
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(Recipient $recipient)
    {
        return redirect(route('apps.mailmerge.recipient.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Recipient $recipient)
    {
        return view('apps.mailmerge.recipient.edit', compact('recipient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, Recipient $recipient)
    {
        $request->validate(
            [
                'name' => ['string', 'max:255', 'required',
                    Rule::unique('recipients')->ignore($recipient->id)],
                'code' => 'string|max:255|required',
            ]
        );

        $recipient->name = $request->get('name');
        $recipient->code = $request->get('code');
        $recipient->updated_by = Auth::user()->id;
        $recipient->save();

        // Check if we need to delete aliases
        $delObj = json_decode($request->del_aliases);
        if ($delObj) {
            foreach ($delObj as $id) {
                $link = Recipient::find(substr($id, 1));

                // Just a sanity check
                if ($link->link == $recipient->name) {
                    $link->delete();
                }
            }
        }

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', __('Recipient updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Recipient $recipient)
    {
        $recipient->delete();

        return redirect(route('apps.mailmerge.recipient.index'))->with('status', __('Recipient deleted!'));
    }

    /**
     * Return a listing of the resource in json.
     *
     * @return Response
     */
    public function list()
    {
        $this->authorize('viewAny', Recipient::class);
        $recipients = Recipient::all(['name', 'code', 'link']);

        return response()->json($recipients);
    }

    /**
     * Store a newly created list of resources in storage.
     *
     * @return Response
     */
    public function storeMany(Request $request)
    {
        if ($request->user()->cannot('create', Recipient::class)) {
            abort(403);
        }

        $request->validate(
            [
                'many.*.name' => 'unique:recipients,name|required|string',
                'many.*.code' => 'required|string',
                'many.*.link' => 'required|string',
            ]
        );

        if (! $request->has('many')) {
            return response('Wrong post', 500);
        }

        $request->whenHas(
            'many',
            function ($input): void {
                $recipients = collect();

                foreach ($input as $item) {
                    $recipients->push(
                        Recipient::make(
                            [
                                'name' => StringConverter::removeAccents($item['name']),
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
