@extends('layouts.admin')

@php

    $breadCrumbs = [
        [
            'name' => 'dashboard',
            'url' => route('admin.dashboard'),
        ],
        [
            'name' => 'Cms',
            'url' => null,
        ],
        [
            'name' => $cms->title,
            'url' => null,
        ],
    ];
@endphp

@section('content')
    @include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])
    <div class="px-4">
        <div class="  bg-white  rounded-lg">
            <form id="cmsForm" class="box flex flex-col  relative text-center  shadow-md">
                <input type="hidden" id="slug" value="{{ $cms->slug }}"></input>
                <h1 class="label p-3 border-b  text-2xl font-semibold">
                    {{ $cms->title }}
                </h1>
                <div class="p-3 border-b">
                    <div class="" id="editor">{!! $cms->content !!}</div>
                </div>
                <div class="flex p-3 justify-end">
                    <button class="btn-primary">
                        Save <div id="spin" class="hidden"><i class="fa fa-spinner fa-spin ml-1"></i></div></button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => editorInstance = editor)
            .catch(error => {
                console.error(error);
            });

        $(document).ready(function() {

            document.getElementById('cmsForm').addEventListener('submit', function(e) {
                e.preventDefault();
                document.getElementById('spin').classList.remove('hidden')
                document.getElementById('spin').disabled = true;
                const slug = document.getElementById('slug').value;
                console.log(slug)
                let url = "{{ route('admin.cms', ['slug' => '__slug__']) }}"
                url = url.replace('__slug__', slug)
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        content: editorInstance.getData()
                    },
                    success: function(response) {
                        console.log(response);
                        document.getElementById('spin').classList.add('hidden')
                        document.getElementById('spin').disabled = false;
                        swalSuccess("Content updated successfully!");
                    },
                    error: function(xhr) {
                        console.log(xhr)
                        document.getElementById('spin').disabled = false;
                        document.getElementById('spin').classList.add('hidden')
                        swalError("An error occurred while updating the content.");
                    }
                });
            })
        })
    </script>
@endpush
