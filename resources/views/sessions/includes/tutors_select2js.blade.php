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
                        strict:"{{$strict??false}}",
                        student_tutoring_package_id :$('#student-tutoring-package-id').val(),
                        email: params.term
                    };
                },
                error: function (xhr, status, error) {
                    $("input[type='submit']").attr("disabled", false);
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, item) {
                            toastr.error(item[0]);
                        });
                    } else if(xhr.status === 404){
                        let response = xhr.responseJSON
                        toastr.error(response.message);
                    } else {
                        toastr.error("something went wrong");
                    }
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
