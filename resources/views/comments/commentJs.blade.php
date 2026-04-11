<script>
window.weightroomRoutes = window.weightroomRoutes || {};
window.weightroomRoutes.deleteComment = "{{ route('deleteComment', ['comment_id' => ':cid'], false) }}";
</script>
<script src="{{ mix('js/comments.js') }}"></script>

<script>
$(document).ready(function(){
    $('.object_comments').collapsible({
        xoffset:'-30',
        symbolhide:'[-]',
        symbolshow:'[+]'
    @if ($commenting)
        , defaulthide:false
    @endif
    });
});
</script>
