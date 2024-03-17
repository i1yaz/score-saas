
@if($status==1)
    <span class="badge badge-success">{{$text_success??''}}</span>
@elseif($status==0 )
    <span class="badge badge-danger">{{$text_danger??''}}</span>
@endif
