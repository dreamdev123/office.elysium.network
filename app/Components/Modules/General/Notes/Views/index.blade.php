<div class="heading">
    <h3>{{ _mt('General-Notes','Notes.Notes') }}</h3>
</div>
<div class="notesWrapper" data-user="{{ $user->id }}">
    <div class="summaryData">
        {{-- @if(getScope() != 'admin') --}}
        <div class="col-md-12">
            <textarea rows="20" style="width: 100%" class="notes-comments" id="{{ $user->id }}-notes-comments"></textarea>
        </div>
        <div class="col-md-12">
            <button class="btn btn-success" id="{{ $user->id }}-notes-save" style=" float: right">save</button>
        </div>
        
        
        {{--@endif--}}
    </div>
</div>
<script>
    "use strict";

    $(function () {
        loadNotes();
    });

    $('#{{ $user->id }}-notes-save').on('click', function (e) {
        e.preventDefault();
        simulateLoading('.notes-comments');
        var route = '{{ route(strtolower(getScope()).'.Notes.saveNotes') }}';
        var id = '{{ $user->id }}';
        var notes = $('#{{ $user->id }}-notes-comments').val();
        var options = {id: id, notes: notes};
        $.post(route, options, function (response) {
            if (response.success) {
                toastr.success("{{ _mt('General-Notes','Notes.save_successfully') }}");
            } else {
                toastr.error("{{ _mt('General-Notes','Notes.save_error') }}");
            }
            
        });
    });

    function loadNotes() {
        simulateLoading('.notes-comments');
        var id = '{{ $user->id }}';
        var options = {id: id};
        $.post('{{ route(strtolower(getScope()).'.Notes.getNotes') }}', options, function (response) {
            $('#{{ $user->id }}-notes-comments').val(response);
        });
    }
</script>

