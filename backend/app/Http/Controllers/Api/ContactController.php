<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * Pengontrol Manajemen Pesan Kontak
 * 
 * Menangani penerimaan pesan dari formulir kontak publik dan manajemen pesan
 * oleh administrator. Mendukung pencarian, filter status, dan paginasi.
 */
class ContactController extends Controller
{
    /**
     * Ambil daftar pesan kontak dengan filter dan paginasi (Admin only)
     * 
     * Menampilkan semua pesan kontak masuk dengan opsi filter berdasarkan status
     * dan pencarian keyword. Hasil dipaginasi maksimal 50 per halaman.
     * 
     * @return \Illuminate\Http\JsonResponse - Daftar pesan dengan paginasi
     */
    public function index()
    {
        $request = request();
        
        // Batasi jumlah item per halaman (maksimal 50)
        $jumlahPerHalaman = min((int) $request->integer('per_page', 10), 50);
        $kueri = Contact::query();

        // Filter berdasarkan status pesan (unread, read, archived)
        if ($status = $request->string('status')->toString()) {
            $kueri->where('status', $status);
        }

        // Pencarian dalam nama, email, subject, atau isi pesan
        if ($cari = $request->string('search')->toString()) {
            $operatorBandingan = config('database.default') === 'pgsql' ? 'ilike' : 'like';
            $kueri->where(function ($q) use ($cari, $operatorBandingan) {
                $q->where('name', $operatorBandingan, "%{$cari}%")
                    ->orWhere('email', $operatorBandingan, "%{$cari}%")
                    ->orWhere('subject', $operatorBandingan, "%{$cari}%")
                    ->orWhere('message', $operatorBandingan, "%{$cari}%");
            });
        }

        return response()->json($kueri->latest()->paginate($jumlahPerHalaman), 200);
    }

    /**
     * Terima dan simpan pesan kontak baru dari formulir publik
     * 
     * Memvalidasi data masukan, sanitasi untuk mencegah XSS, dan menyimpan
     * pesan ke database dengan status awal 'unread'.
     * 
     * @param Request $request - Request berisi nama, email, telpon, subject, dan pesan
     * @return \Illuminate\Http\JsonResponse - Pesan sukses dan data pesan yang disimpan
     */
    public function store(Request $request)
    {
        // Validasi input dari formulir kontak publik
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Sanitasi data untuk mencegah injeksi HTML/XSS
        $validated['name'] = strip_tags(trim($validated['name']));
        if (!empty($validated['subject'])) {
            $validated['subject'] = strip_tags(trim($validated['subject']));
        }
        $validated['message'] = strip_tags(trim($validated['message']));
        
        // Set status awal pesan sebagai belum dibaca
        $validated['status'] = 'unread';

        // Simpan pesan ke database
        $contact = Contact::create($validated);
        
        return response()->json(['message' => 'Pesan terkirim', 'data' => $contact], 201);
    }

    /**
     * Ambil detail pesan kontak tertentu
     * 
     * Menampilkan informasi lengkap dari satu pesan kontak berdasarkan ID.
     * 
     * @param Contact $contact - Model Contact yang akan ditampilkan
     * @return \Illuminate\Http\JsonResponse - Detail pesan kontak
     */
    public function show(Contact $contact)
    {
        return response()->json($contact, 200);
    }

    /**
     * Perbarui status pesan kontak (Admin only)
     * 
     * Mengubah status pesan dari unread menjadi read atau archived untuk
     * pengelolaan pesan yang lebih baik oleh administrator.
     * 
     * @param Request $request - Request berisi status baru
     * @param Contact $contact - Model Contact yang akan diupdate
     * @return \Illuminate\Http\JsonResponse - Data pesan yang sudah diupdate
     */
    public function update(Request $request, Contact $contact)
    {
        // Validasi status hanya boleh: unread, read, atau archived
        $validated = $request->validate([
            'status' => 'string|in:unread,read,archived',
        ]);

        // Update status pesan
        $contact->update($validated);
        
        return response()->json($contact, 200);
    }

    /**
     * Hapus pesan kontak (Admin only)
     * 
     * Menghapus pesan kontak dari database secara permanen.
     * 
     * @param Contact $contact - Model Contact yang akan dihapus
     * @return \Illuminate\Http\JsonResponse - Pesan konfirmasi penghapusan
     */
    public function destroy(Contact $contact)
    {
        // Hapus pesan dari database
        $contact->delete();
        
        return response()->json(['message' => 'Pesan kontak berhasil dihapus'], 200);
    }
}
