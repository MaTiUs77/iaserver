@extends('angular')
@section('title','Service - Barcode Status')
@section('body')

<div class="container">
    <form method="post" action="?">
        <h4> &nbsp;&nbsp; Ingresar lista de barcode: </h4>
        <textarea class="form-control" name="barcodes" style="width: 300px;height: 300px;"></textarea>
        <h4>Buscar en</h4>
        DB Actual   <input type="radio" name="modo" value="current" selected="selected">
        |
        DB 2014 <input type="radio" name="modo" value="old">
        <hr>
        <button type="submit" class="btn btn-primary">Procesar</button>
    </form>
</div>

@include('iaserver.common.footer')
@endsection

