@extends('layouts.app')
@section('header')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>

                    <div class="card-body">
                        <form method="POST" action="/threads">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="channel_id">Choose a channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Choose one...</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id')==$channel->id?'selected':'' }}>{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Title:</label>

                                <input type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title') }}"
                                       class="form-control {{ $errors->any()?$errors->has('title')?'is-invalid':'is-valid':'' }}"
                                       required>
                                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                                <div class="valid-feedback">Title is good.</div>
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <wysiwyg name="body"></wysiwyg>
                                <div class="invalid-feedback">{{ $errors->first('body') }}</div>
                                <div class="valid-feedback">Body is good.</div>
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LdB3XIUAAAAAGvqW4GKb0_AWyEwLw3XX2dw_Owv"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>

                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection