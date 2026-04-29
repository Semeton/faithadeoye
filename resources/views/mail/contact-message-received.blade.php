<x-mail::message>
# New message from {{ $message->name }}

**From:** {{ $message->name }} ({{ $message->email }})
@if($message->subject)
**Subject:** {{ $message->subject }}
@endif
**Received:** {{ $message->received_at->format('d M Y, g:ia') }}

---

{{ $message->body }}

---

<x-mail::button :url="url('/admin/messages')">
View in Admin
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
