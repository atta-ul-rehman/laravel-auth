@extends('auth::layouts.app')

@section('content')

<div id='app' class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Chats</div>
                <div class="panel-body">
                    <ul id="messageOutput" class="chat">
                        
                    </ul>
                </div>
                    <div class="panel-footer">
                        <div class="chat-room">
                            <div class="input-area">
                            <form id="chatForm" method="POST" action="/messages">
                            @csrf
                                <input type="text" class="form-control mb-1" id="message-input" placeholder="Enter your message here...">
                                <button  type="submit" class="btn btn-primary" id="send-message">Send</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection