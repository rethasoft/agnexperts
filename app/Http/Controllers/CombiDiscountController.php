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
            'service_ids.*' => 'integer|exists:types,id',
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
            'service_ids.*' => 'integer|exists:types,id',
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
        if (request()->is('app/tenant/*')) {
            return redirect()->route('combi_discount.index')->with('success', 'Combi-korting succesvol verwijderd!');
        }
        return redirect()->route('admin.combi_discounts.index')->with('success', 'Combi indirim silindi!');
    }

    public function checkCombiDiscount(Request $request)
    {
        $serviceIds = $request->input('service_ids', []);
        if (!is_array($serviceIds) || count($serviceIds) < 2) {
            return response()->json(['has_combi' => false]);
        }

        
        $combi = \App\Domain\Inspections\Models\CombiDiscount::where('active', true)
            ->get()
            ->first(function ($item) use ($serviceIds) {
                // Daha esnek: combi tanımındaki tüm hizmetler seçiliyse (fazladan başka hizmet de olsa) indirim uygula
                return empty(array_diff($item->service_ids, $serviceIds));
            });
        if ($combi) {
            return response()->json([
                'has_combi' => true,
                'discount_type' => $combi->discount_type,
                'discount_value' => $combi->discount_value,
                'combi_id' => $combi->id,
                'service_ids' => $combi->service_ids,
            ]);
        }
        return response()->json(['has_combi' => false]);
    }
} 