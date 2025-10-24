<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $ticket->code }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .card { border:1px solid #ddd; padding:20px; max-width:600px; margin:20px auto; }
        h1 { margin:0 0 10px; }
        .muted { color:#666; }
    </style>
</head>
<body>
<div class="card">

    <h1>Event Ticket</h1>
    <p><strong>Code:</strong> {{ $ticket->code }}</p>
    <p><strong>Event:</strong> {{ $ticket->evenement->title }}</p>
    <p><strong>Holder:</strong> {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
    <p><strong>Starts:</strong> {{ $ticket->evenement->starts_at->timezone($ticket->evenement->timezone)->toDayDateTimeString() }}</p>
    <p><strong>Location:</strong> {{ $ticket->evenement->location_text ?? 'Online/To be announced' }}</p>
    <p class="muted">Present this ticket at the entrance. Keep the code private.</p>
</div>
</body>
</html>
