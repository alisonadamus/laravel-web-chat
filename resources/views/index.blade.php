<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <script src="https://js.pusher.com/8.0.2/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="chat border rounded p-3">
        <div class="top d-flex align-items-center mb-3">
            <img src="https://www.pngall.com/wp-content/uploads/15/User-PNG-Photos.png" alt="Avatar"
                 class="rounded-circle" width="50" height="50">
            <div class="name ms-3 h4">John Doe</div>
        </div>

        <div class="messages mb-3">
            @include('receive', ['message' => 'Hello!'])
        </div>

        <div class="bottom">
            <form>
                <div class="input-group">
                    <input type="text" id="message" name="message" class="form-control" placeholder="Enter message..."
                           autocomplete="off">
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {cluster: '{{ env('PUSHER_APP_CLUSTER') }}'});
    const channel = pusher.subscribe('public');

    channel.bind('chat', function (data) {
        console.log('Received data:', data);
        $.post("/receive", {
            _token: '{{ csrf_token() }}',
            message: data.message,
        })
            .done(function (res) {
                console.log('Response from /receive:', res);
                $('.messages > .message').last().after(res);
                $(document).scrollTop($(document).height());
            })
    });

    $('form').submit(function (event) {

        event.preventDefault();
        $.ajax({
            url: "/broadcast",
            method: "POST",
            headers: {
                'X-Socket-Id': pusher.connection.socket_id
            },
            data: {
                _token: '{{ csrf_token() }}',
                message: $("form #message").val(),
            }
        }).done(function (res) {
            $('.messages > .message').last().after(res);
            $("form #message").val('');
            $(document).scrollTop($(document).height());
        });
    });

</script>
</body>
</html>
