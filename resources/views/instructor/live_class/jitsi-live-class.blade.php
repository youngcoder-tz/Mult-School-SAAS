<div class="container" style="text-align: center; margin-top: 50px">
    <div class="bg-page " id="meet">
    </div>
</div>

<script src="https://meet.jit.si/external_api.js"></script>
<script>
    "use strict"
    function jitsiMeeting() {
        var meeting_id = "{{ $liveClass->meeting_id }}";
        const domain = 'meet.jit.si';
        const options = {
            roomName: meeting_id,
            width: 700,
            height: 700,
            parentNode: document.querySelector('#meet'),
            userInfo: {
                email: "{{ auth()->user()->email }}",
                displayName: "{{ auth()->user()->instructor->name ?? auth()->user()->student->name ?? auth()->user()->name }}",
            },
            configOverwrite: { disableModeratorIndicator: true, localRecording: {disabled:true} }
        }
        const api = new JitsiMeetExternalAPI(domain, options);
    }

    window.onload = jitsiMeeting();
</script>

