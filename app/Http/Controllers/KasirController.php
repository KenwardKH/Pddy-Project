<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type = 'diantar')
    {
        $ordersDiantar = [
            ['no' => 1, 'nama' => 'Adam Irawan','nomor_hp' => '081234567890', 'total' => '2750000', 'alamat' => 'Jln Universitas No.4, Medan','pembayaran' => 'Cash','tempo' => '-', 'status' => 'Diproses', 'tanggal' => '20/09/2024'],
            ['no' => 2, 'nama' => 'Budi Setiawan', 'nomor_hp' => '08123546690', 'total' => '1550000', 'alamat' => 'Jln Majapahit No.6, Medan', 'pembayaran' => 'Transfer','tempo' => '-', 'status' => 'Diantar', 'tanggal' => '30/10/2024']
        ];

        $ordersAmbil = [
            ['no' => 1, 'nama' => 'Susan Mahardika', 'nomor_hp' => '083584964093', 'total' => '2300000', 'alamat' => 'Jln Merdeka No.10, Medan','pembayaran' => 'Transfer','tempo' => '-', 'status' => 'Menunggu Pengambilan', 'tanggal' => '24/10/2024'],
            ['no' => 2, 'nama' => 'Rudi Santoso', 'nomor_hp' => '083584964093', 'total' => '2300000', 'alamat' => 'Jln Perintis No.15, Medan','pembayaran' => 'Kredit','tempo' => '21/12/2024', 'status' => 'Selesai', 'tanggal' => '10/11/2024']
        ];

        $orders = $type === 'ambil' ? $ordersAmbil : $ordersDiantar;

        return view('kasir.kasir_status', compact('type', 'orders'));
    }

    public function updateStatus($id, $type)
    {
        // Temukan pesanan berdasarkan ID
        $order = Order::find($id); // Ganti Order dengan model yang sesuai

        if ($order) {
            // Tentukan status berikutnya berdasarkan tipe dan status saat ini
            if ($type === 'diantar') {
                // Urutan status untuk pesanan "Diantar"
                switch ($order->status) {
                    case 'Diproses':
                        $order->status = 'Diantar';
                        break;
                    case 'Diantar':
                        $order->status = 'Selesai';
                        break;
                    default:
                        return redirect()->back()->with('error', 'Status tidak valid untuk pesanan diantar');
                }
            } elseif ($type === 'ambil') {
                // Urutan status untuk pesanan "Ambil Sendiri"
                switch ($order->status) {
                    case 'Diproses':
                        $order->status = 'Menunggu Pengambilan';
                        break;
                    case 'Menunggu Pengambilan':
                        $order->status = 'Selesai';
                        break;
                    default:
                        return redirect()->back()->with('error', 'Status tidak valid untuk pesanan ambil sendiri');
                }
            }

            // Simpan perubahan status
            $order->save();

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
    }


    public function home()
    {
        $data = [
            'onlineOrders' => 3,
            'unpaidOrders' => 3,
            'processingOrders' => 4,
            'stockRunningLow' => 3,
        ];
        return view('kasir.kasir_home', compact('data'));
    }

    public function stock()
    {
        
        return view('kasir.kasir_stock_barang');
    }

    public function profile()
    {
        
        return view('kasir.kasir_profile');
    }

    public function konfirmasi()
    {
        
        return view('kasir.kasir_konfirmasi');
    }

    public function status()
    {
        
        return view('kasir.kasir_status');
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
}
