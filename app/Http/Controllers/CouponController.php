<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.coupon.';
    }
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view($this->path . 'list', compact('coupons'));
    }

    public function create()
    {
        return view($this->path . 'add');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'data.starts_at' => 'nullable|date',
                'data.expires_at' => 'nullable|date|after:data.starts_at'
            ], [
                'data.starts_at.date' => 'De startdatum moet een geldige datum zijn.',
                'data.expires_at.date' => 'De vervaldatum moet een geldige datum zijn.',
                'data.expires_at.after' => 'De vervaldatum moet na de startdatum liggen.'
            ]);

            $coupon = Coupon::create($request->data);
            if ($coupon) {
                return redirect()->route('coupon.index')
                    ->with('success', 'Coupon created successfully.');
            }
            return redirect()->route('coupon.index')
                ->withErrors('Coupon not created.');
        } catch (\Exception $e) {
            return redirect()->route('coupon.index')
                ->withErrors('Coupon not created.');
        }
    }

    public function edit(Coupon $coupon)
    {
        return view($this->path . 'edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        try {
            $updated = $coupon->update($request->data);
            if ($updated) {
                return redirect()->route('coupon.index')
                    ->with('success', 'Coupon updated successfully.');
            }
            return redirect()->route('coupon.index')
                ->withErrors('Coupon not updated.');
        } catch (\Exception $e) {
            return redirect()->route('coupon.index')
                ->withErrors('Coupon not updated.');
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return redirect()->route('coupon.index')
                ->with('success', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('coupon.index')
                ->withErrors('Coupon not deleted.');
        }
    }
}
