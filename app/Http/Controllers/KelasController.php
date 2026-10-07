<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    // 1. Tampilkan Semua Daftar Kelas
    public function index(): JsonResponse
    {
        $dataKelas = Kelas::all();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Daftar kelas berhasil diambil',
            'data'   => $dataKelas
        ], 200);
    }

    // 2. Tambah Kelas Baru (Admin / Pengelola)
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'pengajar' => 'required|string|max:255',
            'harga'    => 'required|numeric|min:0',
            'kuota'    => 'required|integer|min:1',
        ], [
            'judul.required'    => 'Judul kelas wajib diisi.',
            'pengajar.required' => 'Nama pengajar wajib diisi.',
            'harga.required'    => 'Harga wajib diisi.',
            'harga.numeric'     => 'Harga harus berupa angka.',
            'kuota.required'    => 'Kuota wajib diisi.',
            'kuota.integer'     => 'Kuota harus berupa angka bulat.',
        ]);

        $kelas = Kelas::create([
            'judul'    => $request->judul,
            'pengajar' => $request->pengajar,
            'harga'    => $request->harga,
            'kuota'    => $request->kuota,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Kelas berhasil ditambahkan',
            'data'   => $kelas
        ], 201);
    }

    // 3. Detail Satu Kelas Berdasarkan ID
    public function show($id): JsonResponse
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'sukses' => false,
                'pesan'  => 'Kelas tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Detail kelas berhasil ditemukan',
            'data'   => $kelas
        ], 200);
    }

    // 4. Pendaftaran Kelas & Pengurangan Kuota
    public function daftar(Request $request): JsonResponse
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id'
        ], [
            'kelas_id.required' => 'ID kelas wajib diisi.',
            'kelas_id.exists'   => 'Kelas yang dipilih tidak ditemukan.'
        ]);

        $kelas = Kelas::find($request->kelas_id);

        // Cek sisa kuota
        if ($kelas->kuota <= 0) {
            return response()->json([
                'sukses' => false,
                'pesan'  => 'Pendaftaran gagal! Kuota peserta kelas ini sudah habis.'
            ], 400);
        }

        // Kurangi kuota 1
        $kelas->decrement('kuota', 1);

        // Generate Kode Unik Pendaftaran (Contoh: KELAS-9A21X8LP)
        $kodePendaftaran = 'KELAS-' . strtoupper(Str::random(8));

        // Simpan data pendaftaran
        $pendaftaran = Pendaftaran::create([
            'user_id'          => $request->user()->id,
            'kelas_id'         => $kelas->id,
            'kode_pendaftaran' => $kodePendaftaran,
            'status'           => 'SUKSES'
        ]);

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Berhasil mendaftar kelas bootcamp!',
            'data'   => [
                'kode_pendaftaran' => $pendaftaran->kode_pendaftaran,
                'judul_kelas'      => $kelas->judul,
                'pengajar'         => $kelas->pengajar,
                'harga'            => $kelas->harga,
                'status'           => $pendaftaran->status,
                'dibuat_pada'      => $pendaftaran->created_at->format('Y-m-d H:i:s')
            ]
        ], 201);
    }

    // 5. Lihat Daftar Kelas yang Sudah Didaftarkan oleh User Login
    public function kelasSaya(Request $request): JsonResponse
    {
        $pendaftaranSaya = Pendaftaran::with('kelas')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Daftar kelas saya berhasil diambil',
            'data'   => $pendaftaranSaya
        ], 200);
    }
}