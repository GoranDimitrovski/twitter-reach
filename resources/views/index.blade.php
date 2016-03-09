@extends('layouts.master')

@section('content')

    <div class="container">
        <div class="col-md-8 col-md-offset-2">

            <div class="page-header text-center">
                <h2>Get the reach of the tweet</h2>
            </div>

            <form class="form-horizontal" role="form" id="twitter_reach">
                <div class="form-group">
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="url" name="url">
                    </div>
                    <button type="submit" class="btn btn-primary col-sm-2">Go</button>
                </div>
            </form>

            <h4 id="errors" class="hidden">An error has occurred</h4>
            <h4 class="text-center success hidden">The reach of the tweet is <b><span></span></b></h4>

            <div class="loading-panel hidden text-center">
                <h4>Please wait</h4>
                <img src="images/ajax-loader.gif"/>
            </div>

        </div>
    </div>

@stop