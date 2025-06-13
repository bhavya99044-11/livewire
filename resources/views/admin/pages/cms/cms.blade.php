
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
        'name'=>$cms->title,
        'url'=>null
    ]

];
@endphp

@section('content')
@include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])
<div class="w-full ">
    <div class="mt-10 mx-auto">
        <form id="cmsForm" class="box mx-20 relative border border-rounded-lg p-6 bg-white shadow-md">
            <input type="hidden" id="slug" value="{{$cms->slug}}"><input>
            <span class="label absolute -top-3 left-1/2 -translate-x-1/2 bg-white px-2 text-sm font-semibold">
                {{$cms->title}}
              </span>    
            <div id="editor">{!!$cms->content!!}</div>
            <div class="flex justify-end mt-4">
                <button class="bg-blue-600 flex flex-row duration-150 items-center justify-center hover:opacity-70 transition-all  font-semibold text-white rounded-lg px-2 py-1">
                    Save  <div id="spin" class="hidden"><i class="fa fa-spinner fa-spin ml-1"></i></div></button>   
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

                $(document).ready(function(){

                    document.getElementById('cmsForm').addEventListener('submit',function(e){
                        e.preventDefault();
                        document.getElementById('spin').classList.remove('hidden')
                        document.getElementById('spin').disabled=true;
        const slug=document.getElementById('slug').value;
        console.log(slug)
        let url="{{route('admin.cms',['slug'=>'__slug__'])}}"
        url=url.replace('__slug__',slug)
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
                document.getElementById('spin').disabled=false;
                swalSuccess("Content updated successfully!");
            },
            error: function(xhr) {
                console.log(xhr)
                document.getElementById('spin').disabled=false;
                document.getElementById('spin').classList.add('hidden')
                swalError("An error occurred while updating the content.");
            }
        });
    })
        })
</script>
@endpush