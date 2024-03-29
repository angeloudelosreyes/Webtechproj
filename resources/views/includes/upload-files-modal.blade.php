<form action="{{route('files.store')}}" enctype="multipart/form-data" method="POST">
    @csrf
    @honeypot
    <input type="hidden" id="folder_id" name="folder_id">
    <input type="hidden" id="folder" name="folder">
    <div class="modal component fade" id="create_files" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="caption"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="row gy-4">
                        <div class="col-12">
                            <div>
                                <label for="title" class="form-label">Upload Files (.pdf, .docx, .txt)</label>
                                <input type="file" multiple class="form-control" accept=".pdf,.docx,.txt" name="files[]">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn freeze btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>
