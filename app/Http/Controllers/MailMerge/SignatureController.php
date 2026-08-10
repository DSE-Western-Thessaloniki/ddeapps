<?php

namespace App\Http\Controllers\MailMerge;

use App\Http\Controllers\Controller;
use App\Models\MailMerge\Signature;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SignatureController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Signature::class, 'signature');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $signatures = Signature::with('creator')->get();

        return view('apps.mailmerge.signature.index')->with('signatures', $signatures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('apps.mailmerge.signature.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|max:255|required',
            'text' => 'string|max:65535',
            'active' => 'boolean',
        ]);

        $signature = new Signature([
            'title' => $request->get('title'),
            'text' => $request->get('text'),
            'active' => $request->get('active') == 1 ? 1 : 0,
            'updated_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ]);
        $signature->save();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', __('Signature saved!'));
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(Signature $signature)
    {
        return view('apps.mailmerge.signature.show', compact('signature'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Signature $signature)
    {
        return view('apps.mailmerge.signature.edit', compact('signature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, Signature $signature)
    {
        $request->validate([
            'title' => 'string|max:255|required',
            'text' => 'string|max:65535',
            'active' => 'boolean',
        ]);

        $signature->title = $request->get('title');
        $signature->text = $request->get('text');
        $signature->active = $request->get('active') == 1 ? 1 : 0;
        $signature->updated_by = Auth::user()->id;
        $signature->save();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', __('Signature updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Signature $signature)
    {
        $signature->delete();

        return redirect(route('apps.mailmerge.signature.index'))->with('status', __('Signature deleted!'));
    }
}
