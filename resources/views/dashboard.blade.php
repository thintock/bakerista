@extends('layouts.app')

@section('content')
     <section>
        <div class="container mx-auto p-4">
            <div class="carousel carousel-end rounded-box">
              <div class="carousel-item">
                <img src="{{ asset('images/background.jpg') }}" alt="Drink" style="height:800px;" />
              </div> 
            </div>
        </div>
    </section>
@endsection