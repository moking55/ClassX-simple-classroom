<div style="height:92.1vh;overflow-y:hidden;background: black" class="w-100">
    <iframe allow="camera *;microphone *" src="https://vroom.truevirtualworld.com/<?= $meetInfo->meet_invite ?>" class="w-100 h-100" frameborder="0"></iframe>
</div>
<script>
    function saveEndMeeting() {
        if (<?= $meetInfo->meet_owner ?> == <?= session()->get('id') ?>) {
            console.log('updating data......');

            $.get("/be/endmeet?meetID=<?= $meetInfo->id ?>",
                function(data, textStatus, jqXHR) {
                    return console.log(data);
                },
                "json"
            );
        } else {
            console.log('goodbye! ');
        }
    }

    $(window).bind('beforeunload', function(event) {
        setTimeout(saveEndMeeting(), 0);
    })
</script>