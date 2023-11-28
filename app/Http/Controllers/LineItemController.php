<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLineItemRequest;
use App\Http\Requests\UpdateLineItemRequest;
use App\Models\LineItem;
use App\Models\Tax;
use App\Repositories\LineItemRepository;
use Flash;
use Illuminate\Http\Request;

class LineItemController extends AppBaseController
{
    /** @var LineItemRepository */
    private $lineItemRepository;

    public function __construct(LineItemRepository $lineItemRepo)
    {
        $this->lineItemRepository = $lineItemRepo;
    }

    /**
     * Display a listing of the LineItem.
     */
    public function index(Request $request)
    {
        $lineItems = $this->lineItemRepository->paginate(10);

        return view('line_items.index')
            ->with('lineItems', $lineItems);
    }

    /**
     * Show the form for creating a new LineItem.
     */
    public function create()
    {
        return view('line_items.create');
    }

    /**
     * Store a newly created LineItem in storage.
     */
    public function store(CreateLineItemRequest $request)
    {
        $input = $request->all();

        $lineItem = $this->lineItemRepository->create($input);

        Flash::success('Line Item saved successfully.');

        return redirect(route('line-items.index'));
    }

    /**
     * Display the specified LineItem.
     */
    public function show($id)
    {
        $lineItem = $this->lineItemRepository->find($id);

        if (empty($lineItem)) {
            Flash::error('Line Item not found');

            return redirect(route('line-items.index'));
        }

        return view('line_items.show')->with('lineItem', $lineItem);
    }

    /**
     * Show the form for editing the specified LineItem.
     */
    public function edit($id)
    {
        $lineItem = $this->lineItemRepository->find($id);

        if (empty($lineItem)) {
            Flash::error('Line Item not found');

            return redirect(route('line-items.index'));
        }

        return view('line_items.edit')->with('lineItem', $lineItem);
    }

    /**
     * Update the specified LineItem in storage.
     */
    public function update($id, UpdateLineItemRequest $request)
    {
        $lineItem = $this->lineItemRepository->find($id);

        if (empty($lineItem)) {
            Flash::error('Line Item not found');

            return redirect(route('line-items.index'));
        }

        $lineItem = $this->lineItemRepository->update($request->all(), $id);

        Flash::success('Line Item updated successfully.');

        return redirect(route('line-items.index'));
    }

    /**
     * Remove the specified LineItem from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $lineItem = $this->lineItemRepository->find($id);

        if (empty($lineItem)) {
            Flash::error('Line Item not found');

            return redirect(route('lineItems.index'));
        }

        $this->lineItemRepository->delete($id);

        Flash::success('Line Item deleted successfully.');

        return redirect(route('line-items.index'));
    }

    public function getNewLineItem(Request $request)
    {
        $nextId = $request->nextId;
        $taxes = Tax::active()->select(['id', 'name', 'value'])->get();
        $items = LineItem::active()->select(['id', 'name', 'price'])->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Line Item added',
            'html' => view('line_items.line-item', ['taxes' => $taxes, 'items' => $items, 'id' => $nextId])->render(),
        ]);
    }
}
