<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class GateDeviceController extends Controller
{
    public function View(Request $request)
    {
        $field = ['DeviceID', 'DeviceName'];
        $keyword = $request->input('keyword');

        $devices = DB::table('gate_devices')
                ->where('RecordOwnerID', '=', Auth::user()->RecordOwnerID)
                ->where(function ($query) use($keyword, $field) {
                    if ($keyword) {
                        for ($i = 0; $i < count($field); $i++) {
                            $query->orwhere($field[$i], 'like', '%' . $keyword . '%');
                        }
                    }
                })
                ->get();

        $title = 'Hapus Perangkat !';
        $text = "Anda yakin ingin menghapus perangkat ini?";
        confirmDelete($title, $text);

        return view("Gate.devices", [
            'devices' => $devices
        ]);
    }

    public function ViewJson(Request $request)
    {
        $data = array('success' => false, 'message' => '', 'data' => array());
        $devices = DB::table('gate_devices')->where('RecordOwnerID','=',Auth::user()->RecordOwnerID)->get();
        $data['data'] = $devices;
        return response()->json($data);
    }

    public function Form($id = null)
    {
        $device = DB::table('gate_devices')
                    ->where('id', '=', $id)
                    ->where('RecordOwnerID', '=', Auth::user()->RecordOwnerID)
                    ->get();
        
        $tickets = DB::table('itemmaster')
                    ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                    ->where('Active', 'Y')
                    ->where(function($query) {
                        $query->where('KodeJenisItem', 'TIKET')
                              ->orWhere('KodeJenisItem', 'MEMBER')
                              ->orWhere('NamaItem', 'like', '%tiket%')
                              ->orWhere('NamaItem', 'like', '%member%');
                    })
                    ->get();

        $allowedTickets = [];
        if (count($device) > 0) {
            $allowedTickets = DB::table('gate_device_tickets')
                         ->where('DeviceID', $device[0]->DeviceID)
                         ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                         ->pluck('KodeItem')
                         ->toArray();
        }

        return view("Gate.devices-Input", [
            'device' => $device,
            'tickets' => $tickets,
            'allowedTickets' => $allowedTickets
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'DeviceID' => 'required',
                'DeviceName' => 'required'
            ]);

            $deviceId = $request->input('DeviceID');

            // Check if exists
            $check = DB::table('gate_devices')
                        ->where('DeviceID', $deviceId)
                        ->exists();

            if ($check) {
                alert()->error('Error', 'Device ID sudah terdaftar');
                return redirect()->back();
            }

            $save = DB::table('gate_devices')->insert([
                'DeviceID' => $deviceId,
                'DeviceName' => $request->input('DeviceName'),
                'Status' => $request->input('Status', 1),
                'RecordOwnerID' => Auth::user()->RecordOwnerID,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($save) {
                $allowedTickets = $request->input('AllowedTickets', []);
                foreach($allowedTickets as $kodeItem) {
                    DB::table('gate_device_tickets')->insert([
                        'DeviceID' => $deviceId,
                        'KodeItem' => $kodeItem,
                        'RecordOwnerID' => Auth::user()->RecordOwnerID,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                alert()->success('Success', 'Perangkat Gate berhasil ditambahkan.');
                return redirect('gatedevices');
            } else {
                throw new \Exception('Penambahan Data Perangkat Gagal');
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            alert()->error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required',
                'DeviceID' => 'required',
                'DeviceName' => 'required'
            ]);

            $id = $request->input('id');
            $deviceId = $request->input('DeviceID');

            $check = DB::table('gate_devices')
                        ->where('DeviceID', $deviceId)
                        ->where('id', '<>', $id)
                        ->exists();

            if ($check) {
                alert()->error('Error', 'Device ID sudah terdaftar di perangkat lain');
                return redirect()->back();
            }

            $oldDevice = DB::table('gate_devices')->where('id', $id)->first();

            $update = DB::table('gate_devices')
                ->where('id', $id)
                ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                ->update([
                    'DeviceID' => $deviceId,
                    'DeviceName' => $request->input('DeviceName'),
                    'Status' => $request->input('Status', 1),
                    'updated_at' => now()
                ]);

            if ($oldDevice) {
                DB::table('gate_device_tickets')
                    ->where('DeviceID', $oldDevice->DeviceID)
                    ->where('RecordOwnerID', Auth::user()->RecordOwnerID)
                    ->delete();
            }

            $allowedTickets = $request->input('AllowedTickets', []);
            foreach($allowedTickets as $kodeItem) {
                DB::table('gate_device_tickets')->insert([
                    'DeviceID' => $deviceId,
                    'KodeItem' => $kodeItem,
                    'RecordOwnerID' => Auth::user()->RecordOwnerID,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            alert()->success('Success', 'Data Perangkat berhasil disimpan.');
            return redirect('gatedevices');

        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            alert()->error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function deletedata(Request $request)
    {
        try {
            $device = DB::table('gate_devices')
                    ->where('id', '=', $request->id)
                    ->where('RecordOwnerID', '=', Auth::user()->RecordOwnerID)
                    ->delete();

            if ($device) {
                alert()->success('Success', 'Hapus perangkat berhasil.');
            } else {
                alert()->error('Error', 'Hapus perangkat gagal.');
            }
            return redirect('gatedevices');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            alert()->error('Error', $e->getMessage());
            return redirect()->back();
        }
    }
}
