<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;
use App\Models\Provincesregion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MasterairportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax()) {
            return datatables()->of(Airport::select('*')->orderBy('id', 'desc'))
            ->addColumn('created_at', function ($row) {
                // Format tanggal jadi dd-mm-yyyy HH:MM
                return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function($row){
                 $updateButton = '<a href="' . route('airportdata.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>';
                  $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>';
                 return $updateButton." ".$deleteButton;
            })
            ->rawColumns(['action','created_at'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('pages.master.airport');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $provinces = Provincesregion::all();
        return view('pages.master.createairport', [
            'provinces' => $provinces
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $airport = new Airport();

        $category = !empty($request->category) ? implode(', ', $request->category) : '';

         if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;

            // Simpan di folder 'images' di disk 'public'
            $path = $request->file('image')->storeAs('image/airport', $randomName, 'public');

            // Simpan path ke database
            $airport->image = 'storage/'.$path;

         }

        $airport->province_id = $request->input('province_id');
        $airport->airport_name = $request->input('airport_name');
        $airport->address = $request->input('address');
        $airport->latitude = $request->input('latitude');
        $airport->longitude = $request->input('longitude');
        $airport->telephone = $request->input('telephone');
        $airport->fax = $request->input('fax');
        $airport->email = $request->input('email');
        $airport->website = $request->input('website');
        $airport->category = $category;
        $airport->iata_code = $request->input('iata_code');
        $airport->icao_code = $request->input('icao_code');
        $airport->hrs_of_operation = $request->input('hrs_of_operation');
        $airport->distance_from = $request->input('distance_from');
        $airport->time_zone = $request->input('time_zone');
        $airport->elevation = $request->input('elevation');
        $airport->operator = $request->input('operator');
        $airport->magnetic_variation = $request->input('magnetic_variation');
        $airport->beacon = $request->input('beacon');
        $airport->max_aircraft_capability = $request->input('max_aircraft_capability');
        $airport->air_traffic = $request->input('air_traffic');
        $airport->meteorology_services = $request->input('meteorology_services');
        $airport->aviation_fuel_depot = $request->input('aviation_fuel_depot');
        $airport->supplies_eqipment = $request->input('supplies_eqipment');
        $airport->internet_services = $request->input('internet_services');
        $airport->public_facilities = $request->input('public_facilities');
        $airport->public_transportation = $request->input('public_transportation');
        $airport->note = $request->input('note');
        $airport->nearest_accommodation = $request->input('nearest_accommodation');
        $airport->other_flight_information = $request->input('other_flight_information');
        $airport->other_reference_website = $request->input('other_reference_website');
        $airport->flight_information = $request->input('flight_information');
        $airport->international_flight = $request->input('international_flight');
        $airport->domestic_flights = $request->input('domestic_flights');
        $airport->nearest_medical_facility = $request->input('nearest_medical_facility');
        $airport->nearest_airport = $request->input('nearest_airport');
        $airport->runway_edge_lights = $request->input('runway_edge_lights');
        $airport->reil = $request->input('reil');
        $airport->runways = $request->input('runways');
        $airport->navigation_aids = $request->input('navigation_aids');
        $airport->nearby_airport_navigation_aids = $request->input('nearby_airport_navigation_aids');
        $airport->communication = $request->input('communication');
        $airport->nearest_police_station = $request->input('nearest_police_station');
        $airport->icon = $request->input('icon');

        $airport->save();
        return redirect()->route('airportdata.index')->with('success', 'Data Succesfully Save');
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
    public function edit($id)
    {
        $airport = Airport::findOrFail($id);
        $category = !empty($airport->category) ? explode(', ', $airport->category) : [];
        $provinces = Provincesregion::all();
        return view('pages.master.editairport', [
            'airport' => $airport,
            'category' => $category,
            'provinces' => $provinces
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cari data airport berdasarkan ID
        $airport = Airport::findOrFail($id);
        $category = !empty($request->category) ? implode(', ', $request->category) : '';

        // Update data
        $data = [
            'province_id' => $request->input('province_id'),
            'airport_name' => $request->input('airport_name'),
            'address' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'telephone' => $request->input('telephone'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'category' => $category,
            'iata_code' => $request->input('iata_code'),
            'icao_code' => $request->input('icao_code'),
            'hrs_of_operation' => $request->input('hrs_of_operation'),
            'distance_from' => $request->input('distance_from'),
            'time_zone' => $request->input('time_zone'),
            'elevation' => $request->input('elevation'),
            'operator' => $request->input('operator'),
            'magnetic_variation' => $request->input('magnetic_variation'),
            'beacon' => $request->input('beacon'),
            'max_aircraft_capability' => $request->input('max_aircraft_capability'),
            'air_traffic' => $request->input('air_traffic'),
            'meteorology_services' => $request->input('meteorology_services'),
            'aviation_fuel_depot' => $request->input('aviation_fuel_depot'),
            'supplies_eqipment' => $request->input('supplies_eqipment'),
            'internet_services' => $request->input('internet_services'),
            'public_facilities' => $request->input('public_facilities'),
            'public_transportation' => $request->input('public_transportation'),
            'note' => $request->input('note'),
            'nearest_accommodation' => $request->input('nearest_accommodation'),
            'other_flight_information' => $request->input('other_flight_information'),
            'other_reference_website' => $request->input('other_reference_website'),
            'flight_information' => $request->input('flight_information'),
            'international_flight' => $request->input('international_flight'),
            'domestic_flights' => $request->input('domestic_flights'),
            'nearest_medical_facility' => $request->input('nearest_medical_facility'),
            'nearest_airport' => $request->input('nearest_airport'),
            'runway_edge_lights' => $request->input('runway_edge_lights'),
            'reil' => $request->input('reil'),
            'runways' => $request->input('runways'),
            'navigation_aids' => $request->input('navigation_aids'),
            'nearby_airport_navigation_aids' => $request->input('nearby_airport_navigation_aids'),
            'communication' => $request->input('communication'),
            'nearest_police_station' => $request->input('nearest_police_station'),
            'icon' => $request->input('icon'),
        ];

          if ($request->hasFile('image')) {
            $path = $request->file('image')->store('image/airport', 'public');

            // Hapus gambar lama jika ada
            if ($airport->image && Storage::disk('public')->exists($airport->image)) {
                Storage::disk('public')->delete($airport->image);
            }

             $data['image'] = 'storage/'.$path;

        }

         $airport->update($data);

        // Redirect dengan pesan sukses
        return redirect()->route('airportdata.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Airport::findOrFail($id);

        if($role->delete()){
            $response['success'] = 1;
            $response['msg'] = 'Delete successfully';
        }else{
            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response);
    }
}
