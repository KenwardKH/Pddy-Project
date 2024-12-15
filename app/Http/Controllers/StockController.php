<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pricing;
use App\Models\Supplier;
use App\Models\SupplyInvoice;
use App\Models\SupplyInvoiceDetail;
use App\Models\PricingLog;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    //Daftar Product

    public function product(Request $request)
    {
        $query = $request->input('query'); // Ambil kata kunci pencarian dari input

        // Cari produk berdasarkan nama, deskripsi, atau atribut lainnya
        $products = Product::where('ProductName', 'like', "%{$query}%")
            ->orderByRaw('CASE WHEN CurrentStock < 100 THEN 0 ELSE 1 END') // Prioritaskan stok < 100
            ->orderBy('ProductName', 'asc') // Urutkan alfabetis untuk lainnya
            ->paginate(10);

        return view('owner.produk', compact('products', 'query'));
    }

    public function getProductPricingHistory($id)
    {
        try {
            $price = PricingLog::where('ProductID', $id)->orderBy('timeChanged', 'desc')->get();
    
            // Map the price details
            $details = $price->map(function ($detail) {
                return [
                    'price' => $detail->OldPrice,
                    'timeChanged' => $detail->TimeChanged,
                ];
            });
    
            \Log::info(['details' => $details]);
    
            return response()->json([
                'details' => $details,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching product price history: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data harga.',
            ], 500);
        }
    }
    


    public function store(Request $request)
    {
        // Validasi data yang masuk
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

    public function supplier(Request $request)
    {
        // Ambil input pencarian dari form
        $query = $request->input('query');
    
        // Query dasar untuk mengambil data supplier
        $suppliers = Supplier::orderBy('SupplierName', 'asc');
    
        // Tambahkan kondisi pencarian jika ada input pencarian
        if ($query) {
            $suppliers = $suppliers->where('SupplierName', 'like', "%{$query}%");
        }
    
        // Ambil data suppliers dengan atau tanpa pencarian
        $suppliers = $suppliers->paginate(10);
    
        // Mengirim data suppliers ke view
        return view('owner.supplier', compact('suppliers', 'query'));
    }
    

    public function storeSupplier(Request $request)
    {
        // Validasi data
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

    public function supplyCreate()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('owner.tambah_supply', compact('suppliers', 'products'));
    }

    public function supplyStore(Request $request)
    {
        $request->validate([
            'SupplierID' => 'required|exists:suppliers,SupplierID',
            'SupplyDate' => 'required|date',
            'SupplyInvoiceNumber' => 'nullable|string',
            'SupplyInvoiceImage' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Perubahan ada di sini
            'ProductID' => 'required|array',
            'Quantity' => 'required|array',
            'SupplyPrice' => 'required|array',
            'Discount' => 'required|array',
        ]);
    
        $imageName = null;
        if ($request->hasFile('SupplyInvoiceImage')) {
            $image = $request->file('SupplyInvoiceImage');
            $imageName = time() . '_' . $image->getClientOriginalName();
    
            // Tentukan path untuk menyimpan file
            $destinationPath = public_path('images/supply_invoice_image');
            // Pindahkan file ke folder tujuan
            $image->move($destinationPath, $imageName);
        }
    
        // Buat array untuk JSON Invoice Details
        $invoiceDetails = [];
        foreach ($request->ProductID as $index => $productID) {
            $subtotal = (float) $request->Quantity[$index] * (float) $request->SupplyPrice[$index]; // Harga sebelum diskon
            $discount = $request->Discount[$index] ? $request->Discount[$index] : '0';
            $finalPrice = $subtotal * (1 - (float)$this->parseDiscount($discount) / 100); // Harga setelah diskon
    
            $invoiceDetails[] = [
                'ProductID' => (int) $productID,
                'Quantity' => (int) $request->Quantity[$index],
                'SupplyPrice' => (float) $request->SupplyPrice[$index],
                'Discount' => $discount,
            ];
        }
    
        // Panggil stored procedure dengan 5 parameter
        try {
            DB::statement('CALL CreateSupplyInvoice(?, ?, ?, ?, ?)', [
                $request->SupplierID,
                $request->SupplyDate,
                $request->SupplyInvoiceNumber ?? 'NULL',
                json_encode($invoiceDetails),
                $imageName,
            ]);
        } catch (\Exception $e) {
            // Logging error ke file log Laravel
            \Log::error('Error inserting supply invoice: ' . $e->getMessage());
            
            // Kembalikan pesan kesalahan ke halaman
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
        
        return redirect()->route('supplyInvoice.create')->with('success', 'Supply berhasil ditambahkan.');
    }    
    
    // Fungsi untuk menghitung diskon (di Controller)
    function parseDiscount($discountStr)
    {
        return array_sum(array_map('floatval', explode('+', $discountStr)));
    }
    

    public function getSupplyInvoiceDetails($id)
    {
         // Query untuk mendapatkan invoice dengan detail dan totalAmount menggunakan fungsi SQL
        $invoice = SupplyInvoice::selectRaw(
            'supply_invoices.*, SupplyInvoiceTotalAmount(supply_invoices.SupplyInvoiceId) as totalAmount'
        )
        ->with(['supplyInvoiceDetail']) // Mengambil relasi detail
        ->findOrFail($id);
    
        // Map the invoice details
        $details = $invoice->supplyInvoiceDetail->map(function ($detail) {
            return [
                'product' => $detail->ProductName, // Mengambil langsung nama produk dari InvoiceDetail
                'price' => $detail->SupplyPrice,        // Mengambil harga dari InvoiceDetail
                'Quantity' => $detail->Quantity, // Mengambil jumlah dari InvoiceDetail
                'productUnit' => $detail->productUnit, // Mengambil satuan dari InvoiceDetail
                'disc' => $detail->discount,
                'total' => $detail->Quantity * $detail->FinalPrice, // Menghitung total
            ];
        });
    
        // Log data for debugging
        \Log::info(['details' => $details, 'totalAmount' => $invoice->totalAmount]);

        return response()->json([
            'details' => $details,
            'totalAmount' => $invoice->totalAmount, // Menyertakan totalAmount ke dalam respons
        ]);
    }

    public function daftarSupply(Request $request)
    {
        // Ambil data filter dari request
        $supplierName = $request->input('supplierName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
    
        // Query dasar
        $query = SupplyInvoice::query();
    
        // Tambahkan filter berdasarkan nama supplier
        if ($supplierName) {
            $query->where('SupplierName', 'LIKE', '%' . $supplierName . '%');
        }
    
        // Tambahkan filter berdasarkan tanggal
        if ($startDate) {
            $query->whereDate('SupplyDate', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('SupplyDate', '<=', $endDate);
        }
    
        // Ambil data dengan detail dan urutkan
        $invoices = SupplyInvoice::selectRaw(
            '*, SupplyInvoiceTotalAmount(SupplyInvoiceId) as totalAmount'
        )
        ->orderBy('SupplyDate', 'desc')
        ->paginate(10);
    
        // Tampilkan view dengan data invoice
        return view('owner.daftar_pembelian', compact('invoices'));
    }
    
}
