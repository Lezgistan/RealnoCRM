<?php
/**
 * @var \App\Models\Users\File $document
 */
?>
@extends('layouts.app')

@section('content')
    <div class="embed-responsive embed-responsive-16by9">
        <iframe src='https://view.officeapps.live.com/op/embed.aspx?src={{$document->getPublicPath()}}'></iframe>
    </div>

@stop

