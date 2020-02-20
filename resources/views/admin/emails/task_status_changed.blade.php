<div>
    we would to inform you that is the status of {{ $ticket->title }} ticket assiged to you changed to {{ $ticket->status ? 'closed' : 'opend' }}.<br />
    the deadline at  {{ $ticket->deadline }}<br />
    <h4>Description</h4>
    <p>{{ $ticket->description }}</p>
</div>

