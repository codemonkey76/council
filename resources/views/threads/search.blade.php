@extends('layouts.app')
@section('header')
    <style>
        .ais-highlight > em {
            background: yellow;
            font-style: normal;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <ais-index
                app-id="{{ config('scout.algolia.id') }}"
                api-key="{{ config('scout.algolia.key') }}"
                index-name="threads"
                query="{{ request('q') }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Search Results</div>
                        <ul class="list-group list-group-flush">
                    <ais-results>
                        <template slot-scope="{ result }">
                            <li class="list-group-item">
                                <a :href="result.path">
                                    <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                </a>
                            </li>
                        </template>
                    </ais-results>
                        </ul>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="card mb-2">
                        <div class="card-header">
                            Search
                        </div>
                        <div class="card-body">
                            <ais-search-box>
                                <ais-input placeholder="Find a thread..." :autofocus="true" class="form-control"></ais-input>
                            </ais-search-box>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-header">
                            Filter by Channel
                        </div>
                        <div class="card-body">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                        </div>
                    </div>
                    @if (count($trending))
                        <div class="card">
                            <div class="card-header">
                                Trending Threads
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach ($trending as $thread)
                                        <li class="list-group-item">
                                            <a href="{{ url($thread->path) }}">
                                                {{ $thread->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </ais-index>
    </div>
@endsection