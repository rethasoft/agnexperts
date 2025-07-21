<?php

namespace App\Http\Controllers;

use App\Domain\Inspections\Repositories\CombiDiscountRepository;
use App\Domain\Inspections\Models\CombiDiscount;
use Illuminate\Http\Request;

class CombiDiscountController extends Controller
{
    protected $repository;

    public function __construct(CombiDiscountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $combis = $this->repository->all();
        if (request()->is('app/tenant/*')) {
            return view('app.tenant.combi_discount.index', compact('combis'));
        }
        return view('admin.combi_discounts.index', compact('combis'));
    }

    public function create()
    {
        if (request()->is('app/tenant/*')) {
            return view('app.tenant.combi_discount.create');
        }
        return view('admin.combi_discounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_ids' => 'required|array|min:2',
            'service_ids.*' => 'integer|exists:services,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'active' => 'nullable',
        ]);
        $data['active'] = $request->has('active');
        $this->repository->create($data);
        if (request()->is('app/tenant/*')) {
            return redirect()->route('combi_discount.index')->with('success', 'Combi-korting succesvol toegevoegd!');
        }
        return redirect()->route('admin.combi_discounts.index')->with('success', 'Combi indirim eklendi!');
    }

    public function edit($id)
    {
        $combi = $this->repository->find($id);
        if (request()->is('app/tenant/*')) {
            return view('app.tenant.combi_discount.edit', compact('combi'));
        }
        return view('admin.combi_discounts.edit', compact('combi'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'service_ids' => 'required|array|min:2',
            'service_ids.*' => 'integer|exists:services,id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'active' => 'nullable',
        ]);
        $data['active'] = $request->has('active');
        $this->repository->update($id, $data);
        if (request()->is('app/tenant/*')) {
            return redirect()->route('combi_discount.index')->with('success', 'Combi-korting succesvol bijgewerkt!');
        }
        return redirect()->route('admin.combi_discounts.index')->with('success', 'Combi indirim güncellendi!');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route('admin.combi_discounts.index')->with('success', 'Combi indirim silindi!');
    }
} 