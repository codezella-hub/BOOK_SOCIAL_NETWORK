<div class="flex items-center gap-2">
    <form method="POST" action="{{ route('events.rsvp', $event) }}">
        @csrf
        <input type="hidden" name="status" value="going">
        <button class="btn" type="submit">I'm Going</button>
    </form>
    <form method="POST" action="{{ route('events.rsvp', $event) }}">
        @csrf
        <input type="hidden" name="status" value="interested">
        <button class="btn" type="submit">Interested</button>
    </form>
    <form method="POST" action="{{ route('events.rsvp', $event) }}">
        @csrf
        <input type="hidden" name="status" value="uninterested">
        <button class="btn" type="submit">Uninterested</button>
    </form>
    @if($userStatus === 'going' && $ticket)
    <a class="btn" href="{{ route('tickets.download', [$event, $ticket]) }}">Download Ticket</a>
    @endif
</div>
@if ($errors->any())
<div class="text-red-600 mt-2">{{ $errors->first() }}</div>
@endif
@if (session('status'))
<div class="text-green-700 mt-2">{{ session('status') }}</div>
@endif
