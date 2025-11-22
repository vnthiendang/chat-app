<!-- resources/views/chat.blade.php -->

@extends('layouts.app')

@section('content')


<link href="{{ mix('../resources/css/app.css') }}" rel="stylesheet">
<div class="container" id="app">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default" id="app">
                <div class="panel-heading">Chats</div>

                <div class="panel-body">
                    <ChatMessages :messages="messages"></ChatMessages>
                </div>
                <div class="panel-footer">
                    <ChatForm
                        v-on:messagesent="addMessage"
                        :user="{{ Auth::user() }}"
                    ></ChatForm>
                </div>
                <div>
                    <ChatBox
                        :conversation="selectedConversation"
                        :messages="messages"
                    ></ChatBox>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('../resources/js/app.js') }}" type="module"></script>
@endsection