@extends('layouts.app')
@section('container')
<div class="row">
    @if(count($query) == 0)
        <div class="col-12">
            <div class="alert alert-warning">You haven't created a folder yet.</div>
        </div>
    @else
    <h5 class="mb-4 text-uppercase fw-bolder">Folders</h5>

    @foreach($query as $data)
    <div class="col-md-2 col-6 folder-card">
        <div class="card  bg-light shadow-none" id="folder-1">
            <div class="card-body">
                <div class="d-flex mb-1">
                    <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-more-2-fill fs-16 align-bottom"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{route('folder.show',['id' => Crypt::encryptString($data->id)])}}" ><i class="bx bx-link me-2"></i> Open Folder</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="create_files('{{Crypt::encryptString($data->id)}}','{{$data->title}}')"><i class="bx bx-upload me-2"></i> Upload Files</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="update_folder('{{Crypt::encryptString($data->id)}}','{{$data->title}}')"><i class="bx bx-pencil me-2"></i> Rename</a></li>
                            <li><a class="dropdown-item" href="{{route('folder.destroy',['id' => Crypt::encryptString($data->id)])}}"><i class="bx bx-trash me-2"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>

                <div class="text-center">
                    <div class="mb-2">
                        <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                    </div>
                    <h6 class="fs-15 folder-name">{{$data->title}}</h6>
                </div>
                <div class=" mt-4 text-center text-muted">
                    <span class="text-uppercase fw-bold "><b>{{$files[$data->id]}}</b> Files</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    {{$query->links()}}
    @endif
</div>

@endsection
@section('custom_js')
<script>$('.home').addClass('active')</script>
@endsection