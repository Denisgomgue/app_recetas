@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Recetas IA</h1>
    <p>Encuentra recetas con los ingredientes que tienes</p>
    
    <form id="search-form" class="search-form">
        <div class="search-container">
            <input type="text" id="ingredient-input" placeholder="Cebolla, tomate..." class="search-input">
            <button type="button" id="search-button" class="search-button">Buscar</button>
        </div>
        
        <div class="ingredients-section">
            <h2>Ingredientes ingresados</h2>
            <div id="ingredients-list" class="ingredients-list"></div>
        </div>
    </form>
    
    <div id="error-message" class="error-message"></div>
    
    <div id="results-container" class="results-container"></div>
</div>
@endsection