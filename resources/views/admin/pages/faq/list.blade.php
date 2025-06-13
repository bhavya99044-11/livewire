
@extends('layouts.admin')

@push('styles')
<style>
    .answer {
        max-height: 0; 
        opacity: 0; 
        overflow: hidden;
        margin-top: 0px;
        transition: all 300ms ease-out, opacity 300ms ease-out;
    }
    .group.active .answer {
        margin-top: 8px;
        max-height: 96px;
        opacity: 1;
    }

    .faqContent{
        cursor: pointer;
    }
</style>
@endpush

@php

$breadCrumbs = [
    [
        'name' => 'dashboard',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Faqs',
        'url' => null,
    ],
   
];
@endphp

@section('content')
@include('admin.components.bread-crumb', ['breadCrumbs' => $breadCrumbs])
<div class="">
    <div class="mt-10 flex flex-col items-center justify-center  box mx-20 relative border border-rounded-lg p-6 bg-white shadow-md">
         <div class="" style=""><i class="fa-solid text-5xl fa-circle-question"></i></div>
         <h3 class="text-2xl font-semibold mt-2">Frequently Asked Questions</h3>

         <div class="flex mt-4 items-center justify-center gap-2 w-5/6">
               <form method="GET" action="{{ route('admin.faqs.index') }}" class="w-full mt-5 flex items-center justify-center">
                    <input name="faq" value="{{request()->query('faq')}}" class="flex-grow w-full border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:border-blue-600 focus:ring-blue-600" type="text" placeholder="Search FAQ..."></input>
                    <button type="submit" hidden></button> <!-- Hidden but triggers form on Enter -->
               </form>
            <button id="newFaq" type="button" class=" bg-blue-600 hover:opacity-70 text-white font-semibold py-2 px-4 rounded-lg flex items-center whitespace-nowrap">
                <i class="fa-solid fa-plus mr-1"></i> Add New FAQ
            </button>
        </div>
        <form id="faqForm" class="w-full flex items-center justify-center">       
        </form>
        <div id="faqList" class="mt-5 faqList w-5/6 space-y-2">
            @foreach($faqs as $faq)
            <div data-id="{{$faq->id}}" data-order="{{$faq->order_id}}" class="faqContent group border flex flex-col border-gray-300 transition-all duration-100 p-4 rounded">
                <div class="question flex items-center justify-between">
                    <span class="questionText text-lg font-semibold">{{$faq->question}}</span>
                    <div class="flex items-center space-x-2">
                        <button class="editFaq text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="deleteFaq text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <button class="questionPopup">
                            <i class="fa-solid fa-chevron-down group-[.active]:rotate-180"></i>
                        </button>
                    </div>
                </div>
                <div class="answer">{{$faq->answer}}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

    $("#faqList").sortable({
      update: function (event, ui) {
        const newOrder = $(this).children().map(function () {
          return $(this).data('id');
        }).get();
        const newOrderIds = $(this).children().map(function () {
          return $(this).data('order');
        }).get();
        $.ajax({
                url:"{{route('admin.faqs.reorder')}}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    newOrder:newOrder,
                    newOrderIds:newOrderIds
                },
                success: function(response) {
                },
                error: function(xhr) {
                    swalError(xhr.statusText)
                }
            });
      }
    });

    $("#faqList").disableSelection();  

    document.getElementById('newFaq').addEventListener('click',function(){
            document.getElementById('faqForm').innerHTML=`
             <div id="submitFaq" class="flex flex-col mt-5 border-rounded-lg border w-5/6 border-gray-400 rounded-lg">
                <div class="p-5  border border-rounded-lg border-b-gray-400">Add New FAQ </div>
                <div class="flex flex-col p-5" id="faqSection">
                    <label class=" mt-2">Question</label>
                    <input id="question" class="border p-2 rounded-lg border-gray-300" type="text" placeholder="Enter Your Question" name="question"></input>

                    <label class="mt-2">Answer</label>
                    <textarea id="answer" class="border p-2 rounded-lg border-gray-300" type="text" placeholder="Enter Your Answer" name="answer"></textarea>
                    <div class="flex justify-end mt-4 gap-3">
                        <button id="removeForm" type="button" class="p-2 text-blue-600 border border-blue-600 rounded font-semibold">Cancel</button>
                        <button type="submit" class="p-2 bg-blue-600 rounded-lg font-semibold text-white"><i class="fa-solid fa-plus mr-1"></i> Add FAQ</button>
                    </div>
                </div>
            </div>
            `
    })
    $(document).on('click','#removeForm',function(){
        document.getElementById('faqForm').innerHTML='';
    });

    $(document).on('click','.questionPopup',function(){
        let parent = $(this).closest('.faqContent');
        if(parent.hasClass('active')){
         parent.removeClass('active')
       }else{
        parent.addClass('active')
       }
    })

    $(document).on('submit','#faqForm',function(e){
        e.preventDefault();
    })

    $('#faqForm').validate({
        rules: {
            'question': {
                required: true,
                minlength: 5
            },
            'answer': {
                required: true,
                minlength: 10
            }
        },
        messages: {
            'question': {
                required: "Please enter a question.",
                minlength: "Your question must be at least 5 characters long."
            },
            'answer': {
                required: "Please enter an answer.",
                minlength: "Your answer must be at least 10 characters long."
            }
        },
        errorElement: 'div',
        errorClass: 'text-red-500 text-sm mt-1',
        submitHandler: function (form) {
            const id=document.getElementById('faqForm').getAttribute('data-id')
            const question=document.getElementById("question").value;
            const answer=document.getElementById('answer').value;
        if(id){
            const url="{{route('admin.faqs.update',['faq'=>'__id__'])}}".replace('__id__',id)
            $.ajax({
                url:url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    question:question,
                    answer:answer,
                    _method: 'PUT',
                },
                success: function(response) {
                    document.getElementById('faqForm').innerHTML='';
                    window.location.reload()
                },
                error: function(xhr) {
                    console.log(xhr)
                    swalError(xhr)
                }
            });
        }else{
            $.ajax({
                url: "{{route('admin.faqs.store')}}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    question:question,
                    answer:answer
                },
                success: function(response) {
                    document.getElementById('faqForm').innerHTML='';
                    appendFaq(response);
                
                },
                error: function(xhr) {
                    console.log(xhr)
                    swalError(xhr)
                }
            });
        }
      }
    });
    
    function appendFaq(response){
        document.getElementById('faqList').insertAdjacentHTML('beforeend',
            `<div data-id=${response.data.id} class="faqContent group border flex flex-col  border-gray-300 transition-all duration-100 p-4 rounded">
                <div class="question flex items-center justify-between">
                    <span class="questionText text-lg font-semibold">${response.data.question}</span>
                      <div class="flex items-center space-x-2">
                        <button class="editFaq text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="deleteFaq text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <button class="questionPopup">
                            <i class="fa-solid fa-chevron-down group-[.active]:rotate-180"></i>
                        </button>
                    </div>
                </div>
                <div class="answer">${response.data.answer}</div>
            </div>`
        )

        swalSuccess(response.message);
    }

    $(document).on('click','.deleteFaq',function(){
        const id=$(this).closest('.faqContent').attr('data-id')
        let url="{{route('admin.faqs.destroy',['faq'=>'__id__'])}}".replace('__id__',id)
        let self=$(this)
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                id:id
            },
            success: function(response) {
                console.log(self.parents('.faqContent'))
                self.parents('.faqContent').remove()
                swalSuccess(response.message)
            },
            error: function(xhr) {
                console.log(xhr)
                swalError(xhr)
            }
        });
    });

    $(document).on('click','.editFaq',function(){
        const parent = $(this).parents('.faqContent');
        const id=parent.attr('data-id')
        const question = parent.find('.questionText').text().trim();
        const answer = parent.find('.answer').text().trim();
        editForm(question,answer,id)
    });

    function editForm(question,answer,id){
        console.log(answer)
        document.getElementById('faqForm').innerHTML=`
             <div id="updateFaq" class="flex flex-col mt-5 border-rounded-lg border w-5/6 border-gray-400 rounded-lg">
                <div class="p-5  border border-rounded-lg border-b-gray-400">Add New FAQ </div>
                <div class="flex flex-col p-5" id="faqSection">
                    <label class=" mt-2">Question</label>
                    <input value="${question}" id="question" class="border p-2 rounded-lg border-gray-300" type="text" placeholder="Enter Your Question" name="question"></input>

                    <label class="mt-2">Answer</label>
                    <textarea id="answer" class="border p-2 rounded-lg border-gray-300" type="text" placeholder="Enter Your Answer" name="answer">${answer}</textarea>
                    <div class="flex justify-end mt-4 gap-3">
                        <button type="button" class="p-2 text-blue-600 border border-blue-600 rounded font-semibold">Cancel</button>
                        <button type="submit" class="p-2 bg-blue-600 rounded-lg font-semibold text-white"><i class="fas fa-save"></i> Save FAQ</button>
                    </div>
                </div>
            </div>
            `
            document.getElementById('faqForm').setAttribute('data-id',id)
    }

</script>
@endpush