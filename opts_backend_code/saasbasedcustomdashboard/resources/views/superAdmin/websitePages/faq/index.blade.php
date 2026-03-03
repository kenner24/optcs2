<div class="card">
    <div class="card-header p-2">
        <div class="form-group row">
            <label for="userEmail" class="col-sm-2 col-form-label">Question: </label>
            <div class="col-sm-10">
                <textarea class="summernote-editor" name="{{ $data->page_name . '_question_' . $data->id }}">
                    {!! $data->question !!}
                </textarea>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label for="userEmail" class="col-sm-2 col-form-label">Answer: </label>
            <div class="col-sm-10">
                <textarea class="summernote-editor" name="{{ $data->page_name . '_answer_' . $data->id }}">
                    {!! $data->content !!}
                </textarea>
            </div>
        </div>
    </div>
</div>
