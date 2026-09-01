@extends('layouts.app')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="page-kicker">Collections</p>
        <h1 class="page-title">Editorial edits <em>for real wardrobes.</em></h1>
        <p class="page-intro">Each collection follows the hero’s luxury minimalism while using logo-inspired gold, black, and soft neutral contrast for a differentiated page flow.</p>
    </div>
</section>
<section class="section">
    <div class="container collection-grid">
        @foreach($collections as $key => $collection)
            <a class="collection-card reveal" href="{{ route('collections.show', $key) }}">
                <img src="{{ asset('assets/products/'.$collection['image']) }}" alt="{{ $collection['name'] }}">
                <div><h3>{{ $collection['name'] }}</h3><p>{{ $collection['summary'] }}</p></div>
            </a>
        @endforeach
    </div>
</section>
@endsection
