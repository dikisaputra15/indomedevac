<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;
use App\Models\Provincesregion;
use Illuminate\Support\Facades\DB;
use Exception; // Import Exception for better error handling

class AirportsController extends Controller
{
    /**
     * Display a listing of the resource (for the main map page).
     * This method prepares data for the frontend view.
     */
    public function index(Request $request)
    {
        // Using `unique()` and `values()` on collections ensures distinct, sorted results.
        // `filter()` removes null/empty values.
        $airportNames = Airport::distinct()->pluck('airport_name')->filter()->sort()->values();
        $airportCategories = Airport::distinct()->pluck('category')->filter()->sort()->values();
        $airportLocations = Airport::distinct()->pluck('address')->filter()->sort()->values();

        $provinces = Provincesregion::all(); // Get all provinces

        return view('pages.airports.index', compact('provinces', 'airportNames', 'airportCategories', 'airportLocations'));
    }

    /**
     * API endpoint to filter airports based on various criteria, including geofencing.
     * This method handles the filtering logic for AJAX requests from the map.
     */
    public function filter(Request $request)
    {
        $query = Airport::query();

        // 1. Filter by Airport Name (case-insensitive search)
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where('airport_name', 'like', '%' . $request->input('name') . '%');
        });

        // 2. Filter by Category (case-insensitive search)
        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->where('category', 'like', '%' . $request->input('category') . '%');
        });

        // 3. Filter by Location (Address - case-insensitive search)
        $query->when($request->filled('location'), function ($q) use ($request) {
            $q->where('address', 'like', '%' . $request->input('location') . '%');
        });

        // 4. Filter by Province IDs
        $query->when($request->filled('provinces'), function ($q) use ($request) {
            // Ensure province IDs are an array and valid integers
            $provinceIds = array_filter((array) $request->input('provinces'), 'is_numeric');
            if (!empty($provinceIds)) {
                $q->whereIn('province_id', $provinceIds);
            }
        });

        // 5. Filter by Radius (Haversine Formula)
        if (
            $request->filled('radius') &&
            $request->filled('center_lat') &&
            $request->filled('center_lng') &&
            is_numeric($request->input('radius')) &&
            $request->input('radius') > 0
        ) {
            $centerLat = (float) $request->input('center_lat');
            $centerLng = (float) $request->input('center_lng');
            $radiusKm = (float) $request->input('radius');

            // Haversine formula calculation for distance in kilometers
            // Note: This requires 'latitude' and 'longitude' columns in your 'airports' table
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                            * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

            $query->selectRaw("airports.*, $haversine AS distance", [
                    $centerLat, $centerLng, $centerLat
                ])
                ->whereRaw("$haversine < ?", [
                    $centerLat, $centerLng, $centerLat, $radiusKm // Pass radius for comparison
                ])
                ->orderBy('distance');
        }

        // 6. Filter by GeoJSON Polygon (Geofencing)
        // This assumes you are using a database with spatial extensions like PostGIS or MySQL Spatial.
        // For PostGIS, you'd typically use `ST_GeomFromGeoJSON` and `ST_Within` or `ST_Intersects`.
        // For MySQL, you'd use `ST_GeomFromText` (from WKT) and `ST_Within` or `ST_Intersects`.
        // The implementation here is a placeholder and assumes a PostGIS setup or similar.
       if ($request->filled('polygon')) {
            try {
                $polygonGeoJSON = json_decode($request->input('polygon'), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($polygonGeoJSON['geometry']['coordinates'])) {
                    $geometryType = $polygonGeoJSON['geometry']['type'];

                    if ($geometryType === 'Polygon') {
                        // Ambil koordinat luar dari polygon
                        $coordinates = $polygonGeoJSON['geometry']['coordinates'][0]; // Koordinat luar (outer ring)

                        // Konversi ke format WKT: "lng lat"
                        $wktCoords = implode(',', array_map(function ($point) {
                            return $point[0] . ' ' . $point[1]; // lng lat
                        }, $coordinates));

                        // Buat string WKT POLYGON
                        $wktPolygon = "POLYGON(($wktCoords))";

                        // Gunakan ST_Within + ST_GeomFromText (MySQL Spatial)
                        $query->whereRaw("ST_Within(POINT(longitude, latitude), ST_GeomFromText(?))", [$wktPolygon]);

                    } elseif ($geometryType === 'Point' && isset($polygonGeoJSON['properties']['radius'])) {
                        // Tangani Circle (Leaflet.draw) menggunakan Haversine
                        $centerLat = $polygonGeoJSON['geometry']['coordinates'][1]; // y = lat
                        $centerLng = $polygonGeoJSON['geometry']['coordinates'][0]; // x = lng
                        $radiusMeters = $polygonGeoJSON['properties']['radius'];

                        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                                        * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

                        $query->whereRaw("$haversine < ?", [
                            $centerLat, $centerLng, $centerLat, $radiusMeters / 1000 // m to km
                        ]);
                    } else {
                        // \Log::warning("Unsupported GeoJSON geometry type: " . $geometryType);
                    }
                } else {
                    // \Log::warning("Invalid or malformed GeoJSON: " . $request->input('polygon'));
                }
            } catch (Exception $e) {
                // \Log::error("Error processing polygon filter: " . $e->getMessage());
            }
        }


        // Execute the query and return JSON response
        $airports = $query->get();
        return response()->json($airports);
    }

    // Unchanged methods for other functionalities

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function showdetail($id)
    {
        $airport = Airport::findOrFail($id);
        return view('pages.airports.showdetail', compact('airport'));
    }

    public function showdetailemergency($id)
    {
        $airport = Airport::findOrFail($id);
        $nearests = DB::table('nearestairports')->where('airport_id', $id)->get();
        return view('pages.airports.showdetailemergency', compact('airport', 'nearests'));
    }

    public function showairlinesdestination($id)
    {
        $airport = Airport::findOrFail($id);
        $internationals = DB::table('internationalairlines')->where('airport_id', $id)->get();
        $domestics = DB::table('domesticairlines')->where('airport_id', $id)->get();
        return view('pages.airports.showairlinesdestination', compact('airport', 'internationals', 'domestics'));
    }

    public function shownavigation($id)
    {
        $airport = Airport::findOrFail($id);
        $airportcommunications = DB::table('airportcommunications')->where('airport_id', $id)->get();
        $runawayairports = DB::table('runawayairports')->where('airport_id', $id)->get();
        $navigationaidairports = DB::table('navigationaidairports')->where('airport_id', $id)->get();
        $navigationnearbyairports = DB::table('navigationnearbyairports')->where('airport_id', $id)->get();
        return view('pages.airports.shownavigation', compact('airportcommunications', 'runawayairports', 'navigationaidairports', 'navigationnearbyairports', 'airport'));
    }
}
