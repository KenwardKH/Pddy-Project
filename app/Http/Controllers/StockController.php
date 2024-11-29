<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pricing;
use App\Models\Supplier;
use App\Models\SupplyInvoiceDetail;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    //Daftar Product

    public function product()
    {
        // Ambil data produk beserta relasi ke pricing dan supply_invoice_details
        $products = Product::with(['pricing', 'supplyInvoiceDetail'])->get();

        // Return view dengan data
        return view('owner.produk', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // Ambil kata kunci pencarian dari input

        // Cari produk berdasarkan nama, deskripsi, atau atribut lainnya
        $products = Product::where('ProductName', 'like', "%{$query}%")
            ->get();

        return view('owner.produk', compact('products', 'query'));
    }


    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'ProductName' => 'required|string|max:255',
            'UnitPrice' => 'required|numeric',
            'ProductUnit' => 'required|string|max:50',
            'Description' => 'nullable|string',
            'image' => 'nullable|required|image|mimes:jpeg,png,jpg,gif',
        ]);

        try {
            // Jika ada file gambar, simpan dan set file path
            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
    
                // Tentukan path untuk menyimpan file
                $destinationPath = public_path('images/produk');
    
                // Pindahkan file ke folder tujuan
                $image->move($destinationPath, $imageName);
            }
    
            // Buat data produk
            $product = Product::create([
                'ProductName' => $validated['ProductName'],
                'ProductUnit' => $validated['ProductUnit'],
                'Description' => $validated['Description'],
                'image' => $imageName,
            ]);
    
            // Buat data harga terkait produk
            Pricing::create([
                'ProductID' => $product->ProductID, // Gunakan ProductID dari produk yang baru dibuat
                'UnitPrice' => $validated['UnitPrice'],
            ]);
    
            // Redirect kembali dengan pesan sukses
            return redirect()->route('owner.product')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'ProductName' => 'required|string|max:255',
            'UnitPrice' => 'required|numeric',
            'CurrentStock' => 'required|integer',
            'ProductUnit' => 'required|string|max:50',
            'Description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Tambahkan gif ke validasi
        ]);

        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id);
        $pricing = $product->pricing;

        // Cek apakah gambar baru diunggah
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Tentukan path untuk menyimpan file
            $destinationPath = public_path('images/produk');

            // Pindahkan file ke folder tujuan
            $image->move($destinationPath, $imageName);

            // Hapus gambar lama jika ada
            if ($product->image && file_exists(public_path('images/produk/' . $product->image))) {
                unlink(public_path('images/produk/' . $product->image));
            }

            // Perbarui nama gambar
            $product->image = $imageName;
        }

        // Update data produk lainnya
        $product->update([
            'ProductName' => $validated['ProductName'],
            'ProductUnit' => $validated['ProductUnit'],
            'Description' => $validated['Description'],
            'image' => $product->image ?? $product->getOriginal('image'), // Tetap gunakan gambar lama jika tidak ada gambar baru
        ]);

        // Update data harga terkait produk
        $pricing->update([
            'ProductID' => $product->ProductID, // Gunakan ProductID dari produk yang baru dibuat
            'UnitPrice' => $validated['UnitPrice'],
        ]);

        return redirect()->route('owner.product')->with('success', 'Produk berhasil diperbarui.');
    }



    public function destroyProduct($id)
    {
        // Cari data produk berdasarkan ID
        $product = Product::findOrFail($id);

         // Hapus gambar produk dari folder jika ada
        if ($product->image && file_exists(public_path('images/produk/' . $product->image))) {
            unlink(public_path('images/produk/' . $product->image));
        }

        // Hapus data kasir
        $product->delete();
        $product->pricing()->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('owner.product')->with('success', 'Produk berhasil dihapus.');
    }

    // Daftar Supplier

    public function supplier()
    {
        // Mengambil semua data customer
        $suppliers = Supplier::all();

        // Mengirim data customers ke view
        return view('owner.supplier', compact('suppliers'));
    }

    public function storeSupplier(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'SupplierName' => 'required|string|max:255',
            'SupplierContact' => 'required|string|max:20',
            'SupplierAddress' => 'required|string|max:255',
        ]);

        try {    
            // Buat data supplier
            Supplier::create([
                'SupplierName' => $validated['SupplierName'],
                'SupplierContact' => $validated['SupplierContact'],
                'SupplierAddress' => $validated['SupplierAddress'],
            ]);

            // Redirect kembali dengan pesan sukses
            return redirect()->route('owner.daftar-supplier')->with('success', 'Supplier berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateSupplier(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'SupplierName' => 'required|string|max:255',
            'SupplierContact' => 'required|string|max:20',
            'SupplierAddress' => 'required|string|max:255',
        ]);

        // Cari supplier berdasarkan ID
        $supplier = Supplier::findOrFail($id);

        // Update data produk lainnya
        $supplier->update([
            'SupplierName' => $validated['SupplierName'],
            'SupplierContact' => $validated['SupplierContact'],
            'SupplierAddress' => $validated['SupplierAddress'],
        ]);

        return redirect()->route('owner.daftar-supplier')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroySupplier($id)
    {
        // Cari data produk berdasarkan ID
        $supplier = Supplier::findOrFail($id);

        // Hapus data kasir
        $supplier->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('owner.daftar-supplier')->with('success', 'Supplier berhasil dihapus.');
    }

}
