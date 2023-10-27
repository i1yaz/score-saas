<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Client;
use App\Models\LineItem;
use App\Models\ParentUser;
use App\Models\Tax;
use App\Repositories\TaxRepository;
use Illuminate\Http\Request;
use Flash;

class TaxController extends AppBaseController
{
    /** @var TaxRepository $taxRepository*/
    private $taxRepository;

    public function __construct(TaxRepository $taxRepo)
    {
        $this->taxRepository = $taxRepo;
    }

    /**
     * Display a listing of the Tax.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $email = trim($request->email);
            $clients = Client::select(['clients.id as id', 'clients.email as text'])
                ->active()
                ->where('clients.email', 'LIKE', "%{$email}%")
                ->limit(5)
                ->get();
            return response()->json($clients->toArray());
        }
        $taxes = $this->taxRepository->paginate(10);

        return view('taxes.index')
            ->with('taxes', $taxes);
    }

    /**
     * Show the form for creating a new Tax.
     */
    public function create()
    {
        return view('taxes.create');
    }

    /**
     * Store a newly created Tax in storage.
     */
    public function store(CreateTaxRequest $request)
    {
        $input = $request->all();

        $tax = $this->taxRepository->create($input);

        Flash::success('Tax saved successfully.');

        return redirect(route('taxes.index'));
    }

    /**
     * Display the specified Tax.
     */
    public function show($id)
    {
        $tax = $this->taxRepository->find($id);

        if (empty($tax)) {
            Flash::error('Tax not found');

            return redirect(route('taxes.index'));
        }

        return view('taxes.show')->with('tax', $tax);
    }

    /**
     * Show the form for editing the specified Tax.
     */
    public function edit($id)
    {
        $tax = $this->taxRepository->find($id);

        if (empty($tax)) {
            Flash::error('Tax not found');

            return redirect(route('taxes.index'));
        }

        return view('taxes.edit')->with('tax', $tax);
    }

    /**
     * Update the specified Tax in storage.
     */
    public function update($id, UpdateTaxRequest $request)
    {
        $tax = $this->taxRepository->find($id);

        if (empty($tax)) {
            Flash::error('Tax not found');

            return redirect(route('taxes.index'));
        }

        $tax = $this->taxRepository->update($request->all(), $id);

        Flash::success('Tax updated successfully.');

        return redirect(route('taxes.index'));
    }

    /**
     * Remove the specified Tax from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tax = $this->taxRepository->find($id);

        if (empty($tax)) {
            Flash::error('Tax not found');

            return redirect(route('taxes.index'));
        }

        $this->taxRepository->delete($id);

        Flash::success('Tax deleted successfully.');

        return redirect(route('taxes.index'));
    }
    public function getNewLineItem(Request $request){
        $nextId = $request->nextId;
        $taxes = Tax::active()->select(['id','name','value'])->get();
        $items = LineItem::active()->select(['id','name','price'])->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Line Item added',
            'html' => view('taxes.line-item',['taxes' => $taxes,'items' => $items,'id' => $nextId])->render()
        ]);
    }
}
