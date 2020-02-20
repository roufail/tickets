<div>
    Hello mr {{ $ticket->assign->name }} <br />
    we would to inform you that is the {{ $ticket->title }} ticket assiged to you.<br />
    the deadline at  {{ $ticket->deadline }}<br />
    <h4>Description</h4>
    <p>{{ $ticket->description }}</p>
</div>

