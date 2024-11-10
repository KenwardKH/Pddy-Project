<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'name' => 'Pensil Ajaib 2b', 'price' => 25000, 'available' => 60, 'image' => 'pensil2b.png'],
            ['id' => 2, 'name' => 'Penghapus Ajaib 2b', 'price' => 25000, 'available' => 70, 'image' => 'eraser.png']
        ];
        return view('pengguna.peralatan_kantor', compact('products'));
        // return view('pengguna.peralatan_kantor', compact('products'));
        // return view('pengguna.peralatan_kantor', compact('products'));
        // return view('pengguna.peralatan_kantor', compact('products'));
    }

    public function addToOrder(Request $request)
    {
        // Retrieve quantities from the form input
        $quantities = $request->input('quantity');

        // Initialize or retrieve the current order from the session
        $order = Session::get('order', []);

        foreach ($quantities as $productName => $quantity) {
            if ($quantity > 0) {
                // Check if the product is already in the order
                if (isset($order[$productName])) {
                    // Update the quantity if it already exists
                    $order[$productName]['quantity'] += $quantity;
                } else {
                    // Add new product to the order
                    $order[$productName] = [
                        'name' => $productName,
                        'quantity' => $quantity
                    ];
                }
            }
        }
        
        // Save the order in the session
        Session::put('order', $order);

        return redirect()->route('pengguna.peralatan_kantor')->with('success', 'Produk berhasil ditambahkan ke pesanan!');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function peralatanKantor()
    {
        // Logika untuk menampilkan kategori "Peralatan Kantor"
        return view('pengguna.peralatan_kantor');
    }

    public function peralatanSekolah()
    {
        // Logika untuk menampilkan kategori "Peralatan Sekolah"
        return view('pengguna.peralatan_sekolah');
    }

    public function bukuDanKertas()
    {
        // Logika untuk menampilkan kategori "Buku dan Kertas"
        return view('pengguna.buku_dan_kertas');
    }

    public function pulpenDanPensil()
    {
        // Logika untuk menampilkan kategori "Pulpen dan Pensil"
        return view('pengguna.pulpen_dan_pensil');
    }
}
