<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Pesan
{

    public function valid($request, $data)
    {
        $validator = Validator::make($request->all(), $data, [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah digunakan',
            'min' => ':attribute minimal :min karakter',
            'email' => ':attribute tidak valid'
        ]);
        return $validator;
    }

    public function errorValid($validator)
    {
        $data = $validator->errors();
        $send = [];
        foreach ($data as $row) {
            array_push($send, $row);
        }
        return response()->json([
            'status' => false,
            'pesan' => 'Semua Kolom Wajib Diisi!',
            'data'   => $data
        ], 200);
    }

    public function data($status, $pesan, $number, $data = null, $show = null)
    {
        if ($data != null) {
            if ($show == "show") {
                return response()->json([
                    'status' => $status,
                    'pesan' => $pesan,
                    'data' => $data
                ], $number);
            }
            return response()->json([
                'status' => $status,
                'pesan' => $pesan,
            ], $number);
        }
        return response()->json([
            'status' => $status,
            'pesan' => $pesan
        ], $number);
    }
    public function getAll($data = null)
    {
        if ($data != null) {
            return $this->data(true, "List all data", 200, $data, "show");
        }
        //jika tidak ditemukan
        return $this->data(false, "Data tidak ditemukan", 200);
    }
    public function byId($data = null)
    {
        if ($data != null) {
            return $this->data(true, "Detail data", 200, $data, "show");
        }
        //jika tidak ditemukan
        return $this->data(false, "Data tidak ditemukan", 200);
    }
    public function tambah($data = null)
    {
        if ($data != null) {
            return $this->data(true, "Data berhasil ditambah", 200, $data);
        }
        //jika tidak ditemukan
        return $this->data(false, "Data gagal ditambah", 200);
    }
    public function edit($data = null)
    {
        if ($data != null) {
            return $this->data(true, "Data berhasil diubah", 200, $data);
        }
        //jika tidak ditemukan
        return $this->data(false, "Data gagal diubah", 200);
    }
    public function hapus($data = null)
    {
        if ($data != null) {
            return $this->data(true, "Data berhasil dihapus", 200, $data);
        }
        //jika tidak ditemukan
        return $this->data(false, "Data gagal dihapus", 200);
    }
}
