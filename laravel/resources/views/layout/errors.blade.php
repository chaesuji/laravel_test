@if($errors->any())
    @foreach($errors->all() as $value)
        <div style="color:red;">{{$value}}</div>
    @endforeach
@endif