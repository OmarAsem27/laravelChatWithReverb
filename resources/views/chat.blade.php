@extends('layouts.app')


@section('content')
    <div class="container">
        <h1>Chat with {{ $receiver->name }} </h1>
        <div id="chat-box" class="border p-3" style="height: 400px; overflow-y: scroll;">

            @foreach ($messages as $message)
                <div class="mb-2 {{ $message->sender_id == auth()->id() ? 'text-end' : 'text-start' }}">
                    <span class="badge {{ $message->sender_id == Auth::id() ? 'bg-primary' : 'bg-secondary' }} p-2">
                        {{ $message->message }}
                    </span>
                </div>
            @endforeach

        </div>

        <div id="typing-indicator" class="mt-2 text-muted" style="display: none;">
            {{ $receiver->name }} is typing...
        </div>

        <form id="message-form" class="mt-3">
            <div class="input-group">
                <input type="text" name="message" id="message-input" class="form-control"
                    placeholder="Type a message...">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>

        <script>
            let senderId = {{ Auth::id() }};
            let receiverId = {{ $receiver->id }}
            let CSRFToken = '{{ csrf_token() }}'
        </script>
        <script src="{{ url('chat.js') }}"></script>
    </div>
@endsection
