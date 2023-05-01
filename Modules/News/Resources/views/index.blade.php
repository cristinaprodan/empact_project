@extends('layouts.app')

@section('content')
    <div class="text-center" >
        <img src="{{$data[0]['image_url']}}">
    </div>
    <div class="container">
    <div class="container-border">
        <p><a href="{{$data[0]['link']}}">{{$data[0]['title']}}</a></p>
        <p>Version {{$data['version']}}</p>
    </div>
    <form>
            <div class="row pl-2">
                <div class="col-md-8">
                    <x-search/>
                </div>
                <div class="col-md-4">
                    <x-filters/>
                </div>
            </div>
    </form>
    <div id="loadData"></div>
    <p>{{$data[0]['copyright']}}</p>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        getNews();

        $('#SearchNews').on('keyup', function () {
            getNews();
        });

        function getNews() {
            let formInfo = document.querySelector('form');
            let form = new FormData(formInfo);
            form.append('search_value', $('#SearchNews').val());
            form.append('sort', $('#sort').val());
            form.append('type', $('#sort').find('option:selected').attr('type'));

            $.ajax({
                type: 'POST',
                cache: false,
                url: 'news/get-data',
                data: form,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#loadData").html(data.news);
                },
                error: function () {
                    alert("error");
                }
            });
        }
    </script>

@endsection
