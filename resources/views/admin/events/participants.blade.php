@extends('layouts.admin-layout')
@section('title','Participants')

@section('content')
<section class="part-wrap">
  <div class="part-head">
    <h1>Participants — {{ $event->title }}</h1>
    <div class="head-actions">
      <a href="{{ route('admin.events.edit', $event) }}" class="btn-chip">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" d="M16 3l5 5-11 11H5V13L16 3z"/></svg>
        Edit event
      </a>
    </div>
  </div>

  <div class="part-card">
    <div class="table-responsive">
      <table class="tbl">
        <thead>
        <tr>
          <th>User</th>
          <th>Status</th>
          <th>Ticket</th>
          <th class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($participants as $user)
          @php $ticketId = $user->pivot->ticket_id; @endphp
          <tr>
            <td>
              <div class="user-cell">
                <div class="avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
                <div class="u-meta">
                  <div class="u-name">{{ $user->name }}</div>
                  <div class="u-mail">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge
                {{ $user->pivot->status === 'going' ? 'badge--ok' : ($user->pivot->status === 'interested' ? 'badge--info' : 'badge--muted') }}">
                {{ ucfirst($user->pivot->status) }}
              </span>
            </td>
            <td>
              @if($ticketId)
                @php $ticket = $user->tickets->firstWhere('id', $ticketId); @endphp
                <code class="ticket-code">{{ $ticket?->code }}</code>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td class="actions">
              @if($ticketId && isset($ticket))
                <a class="icon-btn icon-btn--ok" title="Download ticket" href="{{ route('tickets.download', [$event, $ticket]) }}">
                  <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                </a>
              @else
                <span class="muted">—</span>
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <div class="pager">
      {{ $participants->links() }}
    </div>
  </div>
</section>

<style>
.part-wrap { padding: 1.5rem; }
.part-head { display:flex; align-items:center; justify-content:space-between; margin-bottom: .75rem; }
.part-head h1 { margin:0; font-size:1.25rem; font-weight:800; color:#0f172a; }
.head-actions { display:flex; gap:.5rem; }

.part-card { background:#fff; border:1px solid #eaeaea; border-radius:16px; box-shadow:0 10px 24px rgba(5,7,9,.05); overflow:hidden; }

/* table */
.table-responsive { width:100%; overflow:auto; }
.tbl { width:100%; border-collapse:separate; border-spacing:0; }
.tbl thead th {
  text-align:left; font-size:.8rem; letter-spacing:.02em; text-transform:uppercase;
  color:#6b7280; background:#fafafa; padding:12px 14px; border-bottom:1px solid #eee;
}
.tbl tbody td { padding:14px; border-bottom:1px solid #f2f2f2; vertical-align:middle; }
.tbl tbody tr:nth-child(odd) { background: #fff; }
.tbl tbody tr:nth-child(even) { background: #fcfcfd; }
.text-right { text-align:right; }

/* user cell */
.user-cell { display:flex; align-items:center; gap:10px; }
.avatar {
  width:34px; height:34px; border-radius:50%; background:#eef2ff; color:#3730a3;
  display:flex; align-items:center; justify-content:center; font-weight:800;
}
.u-meta { display:grid; }
.u-name { font-weight:700; color:#0f172a; }
.u-mail { font-size:.85rem; color:#6b7280; }

/* badges */
.badge { display:inline-flex; align-items:center; gap:6px; font-weight:700; font-size:.75rem; padding:.25rem .5rem; border-radius:999px; border:1px solid transparent; }
.badge--ok { background:#ecfdf5; color:#047857; border-color:#d1fae5; }
.badge--info { background:#eef2ff; color:#3730a3; border-color:#c7d2fe; }
.badge--muted { background:#f3f4f6; color:#374151; border-color:#e5e7eb; }

.ticket-code { background:#f8fafc; padding:.2rem .35rem; border-radius:6px; border:1px solid #e5e7eb; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace; font-size:.8rem; }

.muted { color:#9ca3af; }

/* actions */
.actions { text-align:right; }
.icon-btn {
  display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px;
  border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#374151;
  transition: background .2s, color .2s, border-color .2s, transform .06s ease;
}
.icon-btn:hover { background:#f9fafb; transform: translateY(-1px); }
.icon-btn--ok { color:#047857; border-color:#d1fae5; background:#ecfdf5; }
.icon-btn--ok:hover { background:#d1fae5; }
.icon { width:16px; height:16px; }

.pager { padding: .75rem 1rem; }
.btn-chip {
  display:inline-flex; align-items:center; gap:8px; padding:.5rem .75rem; border:1px solid #e5e7eb;
  border-radius:10px; background:#fafafa; color:#111827; font-weight:700; text-decoration:none;
}
.btn-chip:hover { background:#eef2ff; border-color:#c7d2fe; color:#3730a3; }
</style>
@endsection
