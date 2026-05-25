<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;
use App\Models\Company;

class GateSerialNumberController extends Controller
{
    public function View(Request $request)
    {
        $keyword = $request->input('keyword');
        $field = ['SerialNumber', 'KodePartner', 'Keterangan'];

        $data = DB::table('gate_serial_numbers')
            ->select('gate_serial_numbers.*')
            ->selectRaw("CASE WHEN gate_serial_numbers.isBlocked = 1 THEN 'BLOCKED' WHEN (SELECT COUNT(*) FROM gate_devices WHERE gate_devices.DeviceID = gate_serial_numbers.SerialNumber) > 0 THEN 'CLAIMED' ELSE 'RELEASED' END as Status")
            ->where('gate_serial_numbers.RecordOwnerID', Auth::user()->RecordOwnerID);

        if ($keyword) {
            $data->where(function ($query) use ($keyword, $field) {
                for ($i = 0; $i < count($field); $i++) {
                    $query->orwhere('gate_serial_numbers.'.$field[$i], 'like', '%' . $keyword . '%');
                }
            });
        }

        return view("Gate.DaftarGateSerialNumber", [
            'data' => $data->get(),
        ]);
    }

    public function Form($id = null)
    {
        $serialNumber = null;
        if ($id && $id != '-') {
            $serialNumber = DB::table('gate_serial_numbers')
                ->where('id', $id)
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->first();
        }

        $companies = Company::all();

        return view("Gate.GateSerialNumber-Input", [
            'serialNumber' => $serialNumber,
            'companies' => $companies,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'SerialNumber' => 'required|unique:gate_serial_numbers,SerialNumber',
                'KodePartner' => 'required'
            ]);

            DB::table('gate_serial_numbers')->insert([
                'SerialNumber' => $request->input('SerialNumber'),
                'KodePartner' => $request->input('KodePartner'),
                'Keterangan' => $request->input('Keterangan'),
                'RecordOwnerID' => Auth::user()->RecordOwnerID,
                'CreatedBy' => Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            alert()->success('Success', 'Serial Number Gate berhasil disimpan.');
            return redirect('gate-serialnumber');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            alert()->error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required',
                'SerialNumber' => 'required|unique:gate_serial_numbers,SerialNumber,' . $request->input('id'),
                'KodePartner' => 'required'
            ]);

            DB::table('gate_serial_numbers')
                ->where('id', $request->input('id'))
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->update([
                    'SerialNumber' => $request->input('SerialNumber'),
                    'KodePartner' => $request->input('KodePartner'),
                    'Keterangan' => $request->input('Keterangan'),
                    'UpdatedBy' => Auth::user()->name,
                    'updated_at' => now(),
                ]);

            alert()->success('Success', 'Serial Number Gate berhasil diperbarui.');
            return redirect('gate-serialnumber');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            alert()->error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function deletedata(Request $request)
    {
        try {
            DB::table('gate_serial_numbers')
                ->where('id', $request->id)
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->delete();

            alert()->success('Success', 'Serial Number Gate berhasil dihapus.');
        } catch (\Exception $e) {
            alert()->error('Error', 'Gagal menghapus Serial Number.');
        }
        return redirect('gate-serialnumber');
    }

    public function generateJson()
    {
        return response()->json([
            'success' => true,
            'serial_number' => $this->generateRandomString(10)
        ]);
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = 'GATE-';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        // Check uniqueness
        $exists = DB::table('gate_serial_numbers')->where('SerialNumber', $randomString)->exists();
        if ($exists) {
            return $this->generateRandomString($length);
        }

        return $randomString;
    }

    public function block(Request $request)
    {
        try {
            DB::table('gate_serial_numbers')
                ->where('SerialNumber', $request->input('SerialNumber'))
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->update([
                    'isBlocked' => 1,
                    'BlockedReason' => $request->input('BlockedReason'),
                    'Keterangan' => 'blocked',
                    'updated_at' => now(),
                ]);

            return response()->json(['success' => true, 'message' => 'Serial Number berhasil diblokir.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function unblock(Request $request)
    {
        try {
            DB::table('gate_serial_numbers')
                ->where('SerialNumber', $request->input('SerialNumber'))
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->update([
                    'isBlocked' => 0,
                    'BlockedReason' => null,
                    'Keterangan' => 'ACTIVE',
                    'updated_at' => now(),
                ]);

            return response()->json(['success' => true, 'message' => 'Serial Number berhasil di-unblock.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
