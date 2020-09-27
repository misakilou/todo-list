@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ajouter une tâche</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif



                    <form id="Form" method="POST">
                        <div class="d-flex" style="margin-bottom: 20px">
                            <input type="text" name="title" class="form-control todo-list-input"
                                placeholder="Titre de la Tâche?">
                            <button class="add btn btn-primary font-weight-bold" id="submit"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </form>

                    <perfect-scrollbar class="ps-show-limits">
                        <div style="position: static;" class="ps ps--active-y">
                            <div class="ps-content">
                                <ul class=" list-group list-group-flush" id="item-list">
                                    @foreach ($tasks as $task)
                                    <li class="list-group-item" id="{{$task->id}}">
                                        <div class="todo-indicator bg-primary"></div>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-2">
                                                    <div class="custom-checkbox custom-control"> <input
                                                            class="custom-control-input" id="Checkbox{{$task->id}}"
                                                            type="checkbox" @if ($task->done) checked @endif><label
                                                            class="custom-control-label"
                                                            for="Checkbox{{$task->id}}">&nbsp;</label> </div>
                                                </div>
                                                <div class="widget-content-left" @if ($task->done)
                                                    style="text-decoration: line-through;" @endif>
                                                    <div class="widget-heading">{{$task->title}}
                                                    </div>
                                                    <div class="widget-subheading"><i>Par {{$task->user->name}}</i>
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <button
                                                        class="border-0 btn-transition btn btn-outline-success btn-add"
                                                        id="{{$task->id}}"> <i class="fas fa-plus"></i></button>
                                                    <button
                                                        class="border-0 btn-transition btn btn-outline-primary btn-edit"
                                                        id="{{$task->id}}"> <i class="fas fa-edit"></i></button>
                                                    <button
                                                        class="border-0 btn-transition btn btn-outline-danger btn-trash"
                                                        id="{{$task->id}}"> <i class="fa fa-trash"></i> </button> </div>
                                            </div>
                                        </div>

                                        <ul>
                                            @php
                                            // $subtasks = App\Task::where('parent_id', $task->id)->get();
                                            @endphp
                                            @foreach ($task->subtasks as $subtask)
                                            <li class="list-group-item" id="{{$subtask->id}}">
                                                <div class="todo-indicator bg-warning"></div>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-2">
                                                            <div class="custom-checkbox custom-control">
                                                                <input class="custom-control-input"
                                                                    id="Checkbox{{$subtask->id}}" type="checkbox"
                                                                    @if($subtask->done) checked @endif>
                                                                <label class="custom-control-label"
                                                                    for="Checkbox{{$subtask->id}}">&nbsp;</label>
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left" @if ($subtask->done)
                                                            style="text-decoration: line-through;" @endif>
                                                            <div class="widget-heading">{{$subtask->title}}
                                                            </div>
                                                            <div class="widget-subheading">
                                                                <i>Par {{$subtask->user->name}}</i></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <button
                                                                class="border-0 btn-transition btn btn-outline-primary btn-edit"
                                                                id="{{$subtask->id}}"> <i
                                                                    class="fas fa-edit"></i></button>
                                                            <button
                                                                class="border-0 btn-transition btn btn-outline-danger btn-trash"
                                                                id="{{$subtask->id}}">
                                                                <i class="fa fa-trash"></i> </button> </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach

                                        </ul>
                                    </li>
                                    @endforeach



                                </ul>
                            </div>
                        </div>
                    </perfect-scrollbar>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
