<ol class="breadcrumb">
    @foreach($bread as $item)
        @if($item == end($bread))
            <li class="active">{{ $item }}</li>
        @else
            <li>{{ $item }}</li>
        @endif
    @endforeach
</ol>