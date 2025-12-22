<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OpenWheatherController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('status', 0)->orderBy('id', 'desc');
        // Mengambil data cuaca dari OpenWeather API
        $apiKey = '49dfb6976043c3864594277b36b26b29'; //key
        $city = 'Banyuwangi'; // kota
        $client = new Client();
        $response = $client->get("http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey");
        $weatherData = json_decode($response->getBody(), true);

        // tampilkan data cuaca pada web
        $weather = [
            'description' => $weatherData['weather'][0]['description'],
            'temperature' => $weatherData['main']['temp'],
            'humidity' => $weatherData['main']['humidity'],
            'wind_speed' => $weatherData['wind']['speed'],
        ];

        return view('cuaca.index', compact('weather', 'notifikasis'));
    }
}
