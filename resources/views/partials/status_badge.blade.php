
@if($status==1)
    <span class="badge badge-success">{{$text_success??''}}</span>
@endif
@if($status==0)
    <span class="badge badge-danger">{{$text_danger??''}}</span>
@endif
