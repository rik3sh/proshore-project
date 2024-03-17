<x-mail::message>
# Hello {{ $name }},
{{--  --}}
A new questiionnare has been assigned to you. Start the test using the link below:

<x-mail::button :url="route('start-exam', $code)">
Start Test
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
