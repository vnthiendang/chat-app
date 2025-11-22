@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
    <div class="p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📸 Create a New Post</h2>

        <form id="createPostForm" method="POST" enctype="multipart/form-data" class="space-y-5">
            {{ csrf_field() }}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                <input type="text" name="caption"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 focus:outline-none"
                    placeholder="What's on your mind?">
            </div>
            <p class="error_msg" id="caption"></p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                <input type="file" name="image"
                    class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
            </div>
            <p class="error_msg" id="image"></p>

            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition">
                Post
            </button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $("#createPostForm").click(function(e) {
        e.preventDefault();

        var _token = $('meta[name="csrf-token"]').attr('content');
        var caption = $('input[name="caption"]').val();
        var image = $('input[name="image"]')[0].files[0];

        $.ajax({
            url: "{{ url('/api/posts') }}",
            type: "POST",
            data: {
                _token: _token,
                caption: caption,
                image: image,
                user_id: 1
            },
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    $(".error_msg").html('');
                    alert(data.success);
                } else {
                    let resp = data.errors;
                        for (index in resp) {
                            $("#" + index).html(resp[index]);
                        }
                }
            }
        });
    });
});

</script>
@endpush
