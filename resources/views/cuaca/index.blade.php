<!-- resources/views/weather/index.blade.php -->

@extends('layouts.app')

@section('title', 'Prediksi Cuaca')

@section('contents')
    <div class="row">
        <div class="col-md-6">
            <h2>Prediksi Cuaca Hari Ini</h2>
            <img src="https://openweathermap.org/img/wn/10d@2x.png" alt="Cuaca" width="100" height="100">
            <p><strong>Cuaca:</strong> {{ $weather['description'] }}</p>
            <p><strong>Temperatur:</strong> {{ $weather['temperature'] }} °C</p>
            <p><strong>Kelembaban:</strong> {{ $weather['humidity'] }}%</p>
            <p><strong>Kecepatan Angin:</strong> {{ $weather['wind_speed'] }} m/s</p>
        </div>
    </div>
@endsection
