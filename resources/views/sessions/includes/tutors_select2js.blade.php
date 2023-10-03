<script type="text/javascript">
    $(document).ready(function () {
        $("#tutor-id").select2({
            theme: 'bootstrap4',
            dropdownAutoWidth: true, width: 'auto',
            minimumInputLength: 3,
            multiple: true,
            ajax: {
                url: "{{route('tutor-email-ajax')}}",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        email: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            placeholder: "Tutor email",
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    });

</script>
